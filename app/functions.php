<?php

use Illuminate\Database\Eloquent\Model;
use App\Foundation\Tire;
use Illuminate\Support\Facades\Cache;
use App\Model\Admin\SensitiveWord;

function parseEntityFieldParams($params)
{
    $items = explode("\n", $params);
    return array_map(function ($item) {
        return explode("=", $item);
    }, $items);
}

function isChecked($value, $options)
{
    return in_array($value, explode(',', $options), true);
}

function xssFilter(Model $data)
{
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
