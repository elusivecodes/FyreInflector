<?php
declare(strict_types=1);

namespace Fyre\Utility;

use Closure;

use function array_keys;
use function array_search;
use function implode;
use function lcfirst;
use function preg_match;
use function preg_replace;
use function str_replace;
use function strtolower;
use function ucwords;

/**
 * Inflector
 */
class Inflector
{
    protected array $cache = [];

    protected array $irregular = [
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

    protected array $plural = [
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

    protected array $singular = [
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

    protected array $uncountable = [
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
     * Convert a delimited string into CamelCase.
     * 
     * @param string $string The input string.
     * @param string $delimiter The delimiter.
     * @return string The CamelCase string.
     */
    public function camelize(string $string, string $delimiter = '_'): string
    {
        return $this->cache(__FUNCTION__.$delimiter, $string, function(string $string) use ($delimiter): string {
            return str_replace(' ', '', $this->humanize($string, $delimiter));
        });
    }

    /**
     * Convert a table_name to a singular ClassName.
     * 
     * @param string $tableName The table name.
     * @return string The classified string.
     */
    public function classify(string $tableName):string
    {
        return $this->cache(__FUNCTION__, $tableName, function(string $tableName): string {
            return $this->camelize($this->singularize($tableName));
        });
    }

    /**
     * Convert a string into kebab-case.
     * 
     * @param string $string The input string.
     * @return string The kebab-case string.
     */
    public function dasherize(string $string): string
    {
        return $this->delimit(str_replace('_', '-', $string), '-');
    }

    /**
     * Convert a string into Human Readable Form.
     * 
     * @param string $string The input string.
     * @param string $delimiter The delimiter.
     * @return string The Human Readable Form string.
     */
    public function humanize(string $string, string $delimiter = '_'): string
    {
        return $this->cache(__FUNCTION__.$delimiter, $string, function(string $string) use ($delimiter): string {
            return ucwords(str_replace($delimiter, ' ', $string));
        });
    }

    /**
     * Get the plural form of a word.
     *
     * @param string $string The input string.
     * @return string The pluralized word.
     */
    public function pluralize(string $string): string
    {
        return $this->cache(__FUNCTION__, $string, function(string $string): string {
            if ($this->isUncountable($string)) {
                return $string;
            }

            if (preg_match('/('.implode('|', array_keys($this->irregular)).')$/i', $string, $match)) {
                $key = $match[1];
                $value = $this->irregular[strtolower($key)];

                return preg_replace('/'.$key.'$/i', $value, $string);
            }

            foreach ($this->plural as $pattern => $replace) {
                if (!preg_match($pattern, $string)) {
                    continue;
                }

                return preg_replace($pattern, $replace, $string);
            }

            return $string;
        });
    }

    /**
     * Add inflection rules.
     * 
     * @param string $type The inflection rule type.
     * @param array $rules The inflection rules.
     * @return static The Inflector.
     */
    public function rules(string $type, array $rules): static
    {
        switch ($type) {
            case 'irregular':
            case 'plural':
            case 'singular':
                $this->$type = array_replace($this->$type, $rules);
                break;
            case 'uncountable':
                $this->uncountable = array_merge($rules, $this->$type);
                break;
            default:
                break;
        }

        $this->cache = [];

        return $this;
    }

    /**
     * Get the singular form of a word.
     *
     * @param string $string The input string.
     * @return string The singularized word.
     */
    public function singularize(string $string): string
    {
        return $this->cache(__FUNCTION__, $string, function(string $string): string {
            if ($this->isUncountable($string)) {
                return $string;
            }

            if (preg_match('/('.implode('|', $this->irregular).')$/i', $string, $match)) {
                $value = $match[1];
                $key = array_search(strtolower($value), $this->irregular);

                return preg_replace('/'.$match[1].'$/i', $key, $string);
            }

            foreach ($this->singular as $pattern => $replace) {
                if (!preg_match($pattern, $string)) {
                    continue;
                }

                return preg_replace($pattern, $replace, $string);
            }

            return $string;
        });
    }

    /**
     * Convert a singular ClassName to a pluralized table_name.
     * 
     * @param string $className The class name.
     * @return string The tableized string.
     */
    public function tableize(string $className): string
    {
        return $this->cache(__FUNCTION__, $className, function(string $string): string {
            return $this->pluralize($this->underscore($string));
        });
    }

    /**
     * Convert a string into snake_case.
     * 
     * @param string $string The input string.
     * @return string The string.
     */
    public function underscore(string $string): string
    {
        return $this->delimit(str_replace('-', '_', $string), '_');
    }

    /**
     * Convert a string into camelBacked.
     * 
     * @param string $string The input string.
     * @return string The string.
     */
    public function variable(string $string): string
    {
        return $this->cache(__FUNCTION__, $string, function(string $string): string {
            return lcfirst($this->camelize($this->underscore($string)));
        });
    }

    /**
     * Retrieve a value from the cache, or generate from a callback if it doesn't exist.
     * 
     * @param string $type The cache type.
     * @param string $value The cache value.
     * @param Closure $callback The callback.
     * @return string The generated value.
     */
    protected function cache(string $type, string $value, Closure $callback): string
    {
        $this->cache[$type] ??= [];

        return $this->cache[$type][$value] ??= $callback($value);
    }

    /**
     * Delimit a camelCase string.
     * 
     * @param string $string The input string.
     * @param string $delimiter The delimiter.
     * @return string The delimited string.
     */
    protected function delimit(string $string, string $delimiter = '_'): string
    {
        return $this->cache(__FUNCTION__.$delimiter, $string, function(string $string) use ($delimiter): string {
            return strtolower(preg_replace('/(?<=\\w)([A-Z])/', $delimiter.'\\1', $string));
        });
    }

    /**
     * Determine whether a word is uncountable.
     *
     * @param string $string The input string.
     * @return bool TRUE if the word is uncountable, otherwise FALSE.
     */
    protected function isUncountable(string $string): bool
    {
        return preg_match('/^('.implode('|', $this->uncountable).')$/i', $string) !== 0;
    }
}
