<?php

use Illuminate\Database\Eloquent\Model;
use App\Foundation\Tire;
use Illuminate\Support\Facades\Cache;
use App\Model\Admin\SensitiveWord;
use App\Model\Admin\Config as SiteConfig;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * 直接从数据库获取系统后台配置
 *
 * @param string $key key
 * @param mixed $default key不存在时的默认值
 * @return mixed key对应的value
 */
function getConfig($key, $default = null)
{
    $v = SiteConfig::where('key', $key)->value('value');
    return !is_null($v) ? $v : $default;
}

/**
 * 后台配置嵌套解析。支持配置值中包含其它配置：{{CONFIG_KEY}}
 *
 * @param string $value
 * @return string
 */
function parseConfig($value)
{
    if (preg_match_all('/\{\{(\w+)\}\}/', $value, $matches)) {
        foreach ($matches[1] as $key => $match) {
            $value = str_replace($matches[0][$key], strval(config('light_config.' . $match)), $value);
        }
    } else {
        return $value;
    }

    return parseConfig($value);
}

function parseEntityFieldParams($params)
{
    if (strpos($params, 'getFormItemsFrom') === 0 && function_exists($params)) {
        $params = call_user_func($params);
    }

    $items = explode("\n", $params);
    return array_map(function ($item) {
        return explode("=", $item);
    }, $items);
}

function isChecked($value, $options)
{
    return in_array($value, explode(',', $options), true);
}

function isCheckedByAnd($value, $options)
{
    return ($options & $value) == $value;
}

function xssFilter($data)
{
    if (is_string($data)) {
        return htmlspecialchars($data, ENT_QUOTES | ENT_SUBSTITUTE, 'utf-8');
    }

    $attributes = $data->getAttributes();
    foreach ($attributes as &$v) {
        if (is_string($v)) {
            $v = htmlspecialchars($v, ENT_QUOTES | ENT_SUBSTITUTE, 'utf-8');
        }
    }
    $data->setRawAttributes($attributes);
}

function initTire()
{
    return Cache::rememberForever('sensitive_words_tire', function () {
        $tires = [];

        foreach (['noun', 'verb', 'exclusive'] as $v) {
            $words = SensitiveWord::query()->select($v)->where($v, '<>', '')->get();

            $tire = new Tire();
            foreach ($words as $k) {
                $tire->add($k->$v);
            }
            $tires[$v] = $tire;
        }

        return $tires;
    });
}

function initTireSingle()
{
    return Cache::rememberForever('sensitive_words_tire_single', function () {
        $types = SensitiveWord::query()->select('type')->groupBy('type')->get();
        $tire = new Tire();
        foreach ($types as $type) {
            $words = SensitiveWord::query()->where('type', $type->type)->get();
            $nouns = [];
            $verbs = [];
            $exclusives = [];
            foreach ($words as $word) {
                if ($word->noun !== '') {
                    $nouns[] = $word->noun;
                } elseif ($word->verb !== '') {
                    $verbs[] = $word->verb;
                } elseif ($word->exclusive !== '') {
                    $exclusives[] = $word->exclusive;
                }
            }

            foreach ($exclusives as $k) {
                $tire->add($k);
            }
            foreach ($verbs as $vk) {
                foreach ($nouns as $nk) {
                    $tire->add($vk . $nk);
                }
            }
        }

        return $tire;
    });
}

function mapTypeToVerbOfSensitiveWords()
{
    return Cache::rememberForever('sensitive_verb_words', function () {
        $words = SensitiveWord::query()->select('verb', 'type')->where('verb', '<>', '')->get();

        $data = [];
        foreach ($words as $word) {
            $data[$word->type <> '' ? $word->type : 'others'][] = $word->verb;
        }

        return $data;
    });
}

/**
 * 敏感词检查
 *
 * @param string $text 待检查文本
 * @param string $type 名词、动词的检测方法。默认为 join 。join：名词和动词相连组合在一起视为违规 all：名词和动词只要同时出现即为违规
 * @param mixed $mode 检查模式。仅 $type 为 all 时有效。默认名词、动词、专用词都检查，显示可指定为 noun verb exclusive
 * @return array
 */
function checkSensitiveWords(string $text, $type = 'join', $mode = null)
{
    if (!is_null($mode) && !in_array($mode, ['noun', 'verb', 'exclusive'])) {
        throw new \InvalidArgumentException('mode参数无效，只能为null值、noun、exclusive');
    }

    if ($type === 'join') {
        $tire = initTireSingle();
        $result = $tire->seek($text);
        return $result;
    }

    $tires = initTire();
    if (!is_null($mode)) {
        return $tires[$mode]->seek($text);
    }

    $result = [];
    $return = [];
    foreach ($tires as $k => $tire) {
        $result[$k] = $tire->seek($text);
    }
    if (!empty($result['noun']) && !empty($result['verb'])) {
        $data = mapTypeToVerbOfSensitiveWords();
        foreach ($result['noun'] as $noun) {
            $type = Cache::rememberForever('sensitive_words_noun_type:' . $noun, function () use ($noun) {
                return SensitiveWord::query()->where('noun', $noun)->value('type');
            });
            $type = $type ? $type : 'others';
            $verbs = array_intersect($data[$type], $result['verb']);
            if (!empty($verbs)) {
                array_push($verbs, $noun);
                $return[] = implode(' ', $verbs);
            }
        }
    }
    return array_merge($return, $result['exclusive']);
}

function isWebp($data)
{
    if (strncmp(substr($data, 8, 7), "WEBPVP8", 7) === 0) {
        return true;
    }

    return false;
}

/**
 * 发送 http 请求
 * @param string $url 目标 url
 * @param string $method 方法
 * @param array $options http 选项
 * @param int $retry 重试次数
 * @return \Psr\Http\Message\ResponseInterface|null
 * @throws GuzzleException
 */
function sendHttpRequest(string $url, string $method = 'GET', array $options = [], int $retry = 3)
{
    $response = null;
    $client = new Client(['timeout' => 5]);
    for ($i = 1; $i <= $retry; $i++) {
        try {
            $response = $client->request($method, $url, $options);
            return $response;
        } catch (GuzzleException $e) {
            if ($i === $retry) {
                throw $e;
            }
            usleep($i * 100000);
            continue;
        }
    }

    return $response;
}

/**
 * 发送钉钉群消息
 *
 * @param string $url
 * @param mixed $content
 * @return \Psr\Http\Message\ResponseInterface
 * @throws GuzzleException
 */
function sendDingGroupMessage(string $url, $content): \Psr\Http\Message\ResponseInterface
{
    $client = new Client(['timeout' => 10]);

    if (is_array($content)) {
        $data = $content;
    } else {
        $data = [
            'msgtype' => 'text',
            'text' => [
                'content' => $content
            ],
            'at' => [
                'isAtAll' => true
            ]
        ];
    }

    return $client->post($url, ['json' => $data]);
}

/**
 * 处理零宽字符
 *
 * @param string $string
 * @return string
 */
function removeZeroWidthCharacters($string)
{
    return preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $string);
}