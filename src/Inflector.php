<?php
declare(strict_types=1);

namespace Fyre\Utility;

use function array_key_exists;
use function array_keys;
use function array_search;
use function ctype_upper;
use function implode;
use function preg_match;
use function preg_replace;
use function strtolower;
use function ucfirst;

/**
 * Inflector
 */
abstract class Inflector
{
    protected static array $cache = [
        'plural' => [],
        'singular' => [],
    ];

    protected static array $irregular = [
        'atlas' => 'atlases',
        'beef' => 'beefs',
        'brief' => 'briefs',
        'brother' => 'brothers',
        'cafe' => 'cafes',
        'child' => 'children',
        'cookie' => 'cookies',
        'corpus' => 'corpuses',
        'cow' => 'cows',
        'criterion' => 'criteria',
        'ganglion' => 'ganglions',
        'genie' => 'genies',
        'genus' => 'genera',
        'graffito' => 'graffiti',
        'hoof' => 'hoofs',
        'loaf' => 'loaves',
        'man' => 'men',
        'money' => 'monies',
        'mongoose' => 'mongooses',
        'move' => 'moves',
        'mythos' => 'mythoi',
        'niche' => 'niches',
        'numen' => 'numina',
        'occiput' => 'occiputs',
        'octopus' => 'octopuses',
        'opus' => 'opuses',
        'ox' => 'oxen',
        'penis' => 'penises',
        'person' => 'people',
        'sex' => 'sexes',
        'soliloquy' => 'soliloquies',
        'testis' => 'testes',
        'trilby' => 'trilbys',
        'turf' => 'turfs',
        'potato' => 'potatoes',
        'hero' => 'heroes',
        'tooth' => 'teeth',
        'goose' => 'geese',
        'foot' => 'feet',
        'foe' => 'foes',
        'sieve' => 'sieves',
        'cache' => 'caches',
    ];

    protected static array $plural = [
        '/(s)tatus$/i' => '$1tatuses',
        '/(quiz)$/i' => '$1zes',
        '/^(ox)$/i' => '$1$2en',
        '/([m|l])ouse$/i' => '$1ice',
        '/(matr|vert)(ix|ex)$/i' => '$1ices',
        '/(x|ch|ss|sh)$/i' => '$1es',
        '/([^aeiouy]|qu)y$/i' => '$1ies',
        '/(hive)$/i' => '$1s',
        '/(chef)$/i' => '$1s',
        '/(?:([^f])fe|([lre])f)$/i' => '$1$2ves',
        '/sis$/i' => 'ses',
        '/([ti])um$/i' => '$1a',
        '/(p)erson$/i' => '$1eople',
        '/(?<!u)(m)an$/i' => '$1en',
        '/(c)hild$/i' => '$1hildren',
        '/(buffal|tomat)o$/i' => '$1$2oes',
        '/(alumn|bacill|cact|foc|fung|nucle|radi|stimul|syllab|termin)us$/i' => '$1i',
        '/us$/i' => 'uses',
        '/(alias)$/i' => '$1es',
        '/(ax|cris|test)is$/i' => '$1es',
        '/s$/' => 's',
        '/^$/' => '',
        '/$/' => 's',
    ];

    protected static array $singular = [
        '/(s)tatuses$/i' => '$1$2tatus',
        '/^(.*)(menu)s$/i' => '$1$2',
        '/(quiz)zes$/i' => '$$1',
        '/(matr)ices$/i' => '$1ix',
        '/(vert|ind)ices$/i' => '$1ex',
        '/^(ox)en/i' => '$1',
        '/(alias)(es)*$/i' => '$1',
        '/(alumn|bacill|cact|foc|fung|nucle|radi|stimul|syllab|termin|viri?)i$/i' => '$1us',
        '/([ftw]ax)es/i' => '$1',
        '/(cris|ax|test)es$/i' => '$1is',
        '/(shoe)s$/i' => '$1',
        '/(o)es$/i' => '$1',
        '/ouses$/' => 'ouse',
        '/([^a])uses$/' => '$1us',
        '/([m|l])ice$/i' => '$1ouse',
        '/(x|ch|ss|sh)es$/i' => '$1',
        '/(m)ovies$/i' => '$1$2ovie',
        '/(s)eries$/i' => '$1$2eries',
        '/([^aeiouy]|qu)ies$/i' => '$1y',
        '/(tive)s$/i' => '$1',
        '/(hive)s$/i' => '$1',
        '/(drive)s$/i' => '$1',
        '/([le])ves$/i' => '$1f',
        '/([^rfoa])ves$/i' => '$1fe',
        '/(^analy)ses$/i' => '$1sis',
        '/(analy|diagno|^ba|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => '$1$2sis',
        '/([ti])a$/i' => '$1um',
        '/(p)eople$/i' => '$1$2erson',
        '/(m)en$/i' => '$1an',
        '/(c)hildren$/i' => '$1$2hild',
        '/(n)ews$/i' => '$1$2ews',
        '/eaus$/' => 'eau',
        '/^(.*us)$/' => '$1',
        '/s$/i' => '',
    ];

    protected static array $uncountable = [
        '.*[nrlm]ese',
        '.*data',
        '.*deer',
        '.*fish',
        '.*measles',
        '.*ois',
        '.*pox',
        '.*sheep',
        // 'people',
        'feedback',
        'stadia',
        '.*?media',
        'chassis',
        'clippers',
        'debris',
        'diabetes',
        'equipment',
        'gallows',
        'graffiti',
        'headquarters',
        'information',
        'innings',
        'news',
        'nexus',
        'pokemon',
        'proceedings',
        'research',
        'sea[- ]bass',
        'series',
        'species',
        'weather',
    ];

    /**
     * Inflect a word based on a count.
     *
     * @param string $word The word.
     * @param float|int $count The count.
     * @return string The inflected word.
     */
    public static function inflect(string $word, float|int $count): string
    {
        return $count == 1 ? $word : static::pluralize($word);
    }

    /**
     * Get the plural form of a word.
     *
     * @param string $word The word.
     * @return string The pluralized word.
     */
    public static function pluralize(string $word): string
    {
        if (array_key_exists($word, static::$cache['plural'])) {
            return static::$cache['plural'][$word];
        }

        if (static::isUncountable($word)) {
            // do nothing
        } else if (preg_match('/('.implode('|', array_keys(static::$irregular)).')$/i', $word, $match)) {
            $key = $match[1];
            $value = static::$irregular[strtolower($key)];

            if (ctype_upper($key[0])) {
                $value = ucfirst($value);
            }

            $word = preg_replace('/'.$key.'$/i', $value, $word);
        } else {
            foreach (static::$plural as $pattern => $replace) {
                if (!preg_match($pattern, $word)) {
                    continue;
                }

                $word = preg_replace($pattern, $replace, $word);
                break;
            }
        }

        return static::$cache['plural'][$word] = $word;
    }

    /**
     * Get the singular form of a word.
     *
     * @param string $word The word.
     * @return string The singularized word.
     */
    public static function singularize(string $word): string
    {
        if (array_key_exists($word, static::$cache['singular'])) {
            return static::$cache['singular'][$word];
        }

        if (static::isUncountable($word)) {
            // do nothing
        } else if (preg_match('/('.implode('|', static::$irregular).')$/i', $word, $match)) {
            $value = $match[1];
            $key = array_search(strtolower($value), static::$irregular);

            if (ctype_upper($value[0])) {
                $key = ucfirst($key);
            }

            $word = preg_replace('/'.$match[1].'$/i', $key, $word);
        } else {
            foreach (static::$singular as $pattern => $replace) {
                if (!preg_match($pattern, $word)) {
                    continue;
                }

                $word = preg_replace($pattern, $replace, $word);
                break;
            }
        }

        return static::$cache['singular'][$word] = $word;
    }

    /**
     * Determine whether a word is uncountable.
     *
     * @param string $word The word.
     * @return bool TRUE if the word is uncountable, otherwise FALSE.
     */
    protected static function isUncountable(string $word): bool
    {
        return preg_match('/^('.implode('|', static::$uncountable).')$/i', $word) !== 0;
    }
}
