<?php
/*! Gustav 1.1.0 | Copyright (c) 2015 Lucas Krause | New BSD License | http://gustav.futape.de */

namespace futape\gustav;

require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), "Gustav.php"));

use Exception, BadMethodCallException, RuntimeException;

class GustavMatch extends Gustav {
    
    
    
    #misc-constants#
    
    #GustavMatch::HOOKS_CLASS#
    /**
     * The name of the class providing hooks for the GustavMatch class.
     * All static functions, public or not, are available via this function.
     *
     * @type string
     */
    const HOOKS_CLASS="futape\gustav\GustavMatchHooks";
    
    
    
    #match-constants#
    
    #GustavMatch::SPEC_LOW#
    /**
     * By default, `GustavMatch::initMatches()` uses a custom word-boundary when matching a source file's title.
     * The *custom word-boundary* consideres, besides the `\b` RegEx escape sequence, `_`s and digits having an adjacent non-digit character, as well as non-digits having an adjacent digit character to separate words.
     * When including this constant's value in the mitmask passed to this class's constructor's $flags parameter, no word-boundary is used at all. The searched term can occur everywhere in the title. This behavior is more loose than the default one.
     *
     * @type int
     */
    const SPEC_LOW=1; //1 = 1<<0
    
    #GustavMatch::SPEC_HIGH#
    /**
     * By default, `GustavMatch::initMatches()` uses a custom word-boundary when matching a source file's title.
     * The *custom word-boundary* consideres, besides the `\b` RegEx escape sequence, `_`s and digits having an adjacent non-digit character, as well as non-digits having an adjacent digit character to separate words.
     * When including this constant's value in the mitmask passed to this class's constructor's $flags parameter, a word-boundary definition of `\b` is used instead which is a bit more strict than the default one.
 item must be found in the title exactly as defined.
     *
     * @type int
     */
    const SPEC_HIGH=2; //2 = 1<<1
    
    #GustavMatch::CASE_SENSITIVE#
    /**
     * By default, `GustavMatch::initMatches()` matches a source file's title case-insensitively.
     * When including this constant's value in the mitmask passed to this class's constructor's $flags parameter, the title is matched case-sensitively instead.
     *
     * @type int
     */
    const CASE_SENSITIVE=4; //4 = 1<<2
    
    #GustavMatch::LITERAL_SPACES#
    /**
     * By default, `GustavMatch::init()` matches any number of any kind of whitespace for a space in a search item or a literal when matching a source file's title.
     * When including this constant's value in the mitmask passed to this class's constructor's $flags parameter, that behavior is disabled. Instead the item must be found in the title exactly as defined.
     *
     * @type int
     */
    const LITERAL_SPACES=8; //8 = 1<<3
    
    
    
    #match-functions#
    
    #GustavMatch::getSearchTermItems()#
    /**
     * Splits a search term's part into single items.
     *
     * Splits a string at whitespace characters.
     * Empty items are removed and each item does only exist a single time within the returned array (case-sensitive).
     * This function's purpose is not to split a search term entered by an user into parts but to split such parts into single items.
     *
     * @param string $search_term_part The search term's part.
     *
     * @return string[] The single search term items.
     */
    protected static function getSearchTermItems($str_items){
        /** /
        return array_unique(explode(" ", preg_replace('/ {2,}/', " ", trim(preg_replace('/\W/u', " ", $str_items)))));
        /*/
        $str_items=preg_replace('/\s+/', " ", trim($str_items));
        
        return $str_items=="" ? array() : array_unique(explode(" ", $str_items));
        /**/
    }
    
    #GustavMatch::processSearchTerm()#
    /**
     * Splits a search term into parts.
     *
     * Parts are separated by whitespace characters.
     * A part which should be treated as a literal or which consists of multiple words can be marked up by wrapping it into double quotes. All characters within such a literal part are taken literally. If you want to mark up a literal double quote, you have to type two double quotes. For example: `one two "three "" four" five` or `""""`.
     * Empty literals are possible.
     * Empty non-literal parts are removed.
     * If no literal has been found in the search term, the entire search term is added to the resulting array as a literal. It gets trimmed and sequences of whitespaces are replaced with 1 simple space.
     * Each part does only exist a single time within the returned array (case-sensitive), regardless of whetehr it's a literal part or not.
     * Usecases of this function's returned value may be as value for the second parameter of GustavSrc::getMatchScore() or as value for one of Gustav::query()'s "match" filter's items. Moreover, you can split a part into single items (mostly relevant for literal parts) by passing the part to GustavBase::getSearchTermItems().
     *
     * @param string $search_term The search term.
     *
     * @return string[] The search term's parts.
     */
    public static function processSearchTerm($str_term){
        $arr_a=explode('"', $str_term);
        $str_items="";
        $arr_literals=array();
        $str_literal="";
        
        /**
         * Just to push the last literal to $arr_literals.
         * Required if the literal is the last part of the search term.
         * If not, an empty string is appended to $str_literal
         * which should be an empty string at this point and gets never
         * pushed to $arr_literals.
         * Othewise the empty string is appended to $str_items which is
         * removed later by calling GustavBase::getSearchTermItems() on
         * $str_items.
         */
        array_push($arr_a, "");
        
        foreach($arr_a as $i=>$val){
            if($i%2!=0){
                $str_literal.=$val;
            }else if($val=="" && $i>0 && $i<count($arr_a)-2){ //last item = `count($arr_a)-1`; last *real* item: `count($arr_a)-2`, since an empty string is pushed to the array above
                $str_literal.='"';
            }else{
                $str_items.=" ".$val;
                
                if($i>0){
                    array_push($arr_literals, $str_literal);
                    
                    $str_literal="";
                }
            }
        }
        
        if(count($arr_literals)==0){
            array_push($arr_literals, preg_replace('/\s+/', " ", trim($str_term))); //Can't use `implode(" ", self::getSearchTermItems($str_term))` since that function calls array_unique() on the array.
        }
        
        $arr_literals=array_filter($arr_literals, function($val){
            return $val!="";
        });
        $arr_items=self::getSearchTermItems($str_items);
        
        return array_unique(array_merge($arr_items, $arr_literals));
    }
    
    
    
    #properties#
    
    #GustavMatch::$src#
    /**
     * The source file whose properties should be matched against the search items.
     *
     * @type GustavSrc
     */
    private $src;
    
    #GustavMatch::$search#
    /**
     * The search items to compare with the source file's properties.
     *
     * @type string[][]
     */
    private $search;
    
    #GustavMatch::$flags#
    /**
     * The flags adjusting the behavior when matching the source file's properties against the search items.
     *
     * @type int
     */
    private $flags;
    
    #GustavMatch::$reWordBoundary#
    /**
     * A regular expression defining the word-boundary wrapping the search items when matching the source file's title.
     *
     * @type string
     */
    private $reWordBoundary;
    
    #GustavMatch::$reMod#
    /**
     * The RegEx modifiers used when matching the source file's title.
     *
     * @type string
     */
    private $reMod;
    
    #GustavMatch::$matches#
    /**
     * The search items matching the source file's properties.
     * Will contain the supported items (`GustavBase::KEY_FILE`, `GustavBase::KEY_TITLE`, `GustavBase::KEY_TAGS`) only, any other item is ignored.
     *
     * @type string[][]
     */
    private $matches;
    
    #GustavMatch::$score#
    /**
     * The match score calculated from the matching search items.
     *
     * @type int
     */
    private $score;
    
    #GustavMatch::$highlight#
    /**
     * The source file's searched properties, HTML encoded and matching parts highlighted using `<mark>`.
     *
     * @type array
     */
    private $highlight;
    
    
    
    #init-functions#
    
    #GustavMatch::__construct()#
    /**
     * A "magic" function that gets called when a new instance of this class is created.
     * If no `GustavSrc` object can be created for the passed path, a `RuntimeException` is thrown.
     * If everything worked properly, the newly created object is initialized.
     *
     * @param string     $path   The path of the source file whose properties should be matched against the search items.
     *                           Gets passed to `GustavSrc::__construct()` which in turn calls `GustavBase::path()` on the path.
     * @param string[][] $search An associative array containing the search items.
     *                           The array's items should use one of the `GustavBase::KEY_*` constants as key and an array of strings containing the search items as value. The values may be an array returned by `GustavBase::processSearchTerm()` for example.
     * @param int        $flags  OPTIONAL | Default: 0
     *                           A bitmask of the following values: `GustavMatch::SPEC_LOW`, `GustavMatch::SPEC_HIGH`, `GustavMatch::CASE_SENSITIVE`.
     *                           See those constants for more information.
     *
     * @return void
     */
    public function __construct($str_path, $arr_search, $int_flags=0){
        try {
            $this->src=new GustavSrc($str_path);
        } catch(Exception $e) {
            throw new RuntimeException("Couldn't process source file.");
        }
        
        $this->initSearch($arr_search); //$search
        $this->flags=$int_flags;
        $this->initRegex(); //$reWordBoundary and $reMod; relies on $flags
        $this->init(); //$matches, $score and $highlight; relies on $re*, $src, $search and $flags
    }
    
    #GustavMatch::initSearch()#
    /**
     * Initializes the array containing the search items (`$search`).
     * Empty items are filtered out.
     *
     * @param string[][] $search An associative array containing the search items.
     *                           The array's items should use one of the `GustavBase::KEY_*` constants as key and an array of strings containing the search items as value.
     *
     * @return void
     */
    private function initSearch($arr_search){
        $this->search=array_map(function($val){
            return array_filter($val, function($val){
                return $val!="";
            });
        }, $arr_search);
    }
    
    #GustavMatch::initRegex()#
    /**
     * Initializes the word-boundary (`$reWordBoundary`) and modifier (`$reMod`) used in regular expressions based on the passed flags.
     *
     * @return void
     */
    private function initRegex(){
        $int_flags=$this->flags;
        
        $arr_sep=array();
        $arr_mod=array();
        
        if(!($int_flags&self::SPEC_LOW)){ //high or mid
            array_push($arr_mod, "u");
            array_push($arr_sep, '(?=[\W_]|$)', '(?<=[\W_]|^)'); //you should always combine this with the "u" pcre modifier (see above)
            
            if(!($int_flags&self::SPEC_HIGH)){ //mid
                array_push($arr_sep, '(?<=\d)(?=\D)', '(?<=\D)(?=\d)'); //auch `(?<=[a-z])(?=[A-Z])|(?<=[A-Z])(?=[a-z])` ?
            }
        }
        
        if(!($int_flags&self::CASE_SENSITIVE)){
            array_push($arr_mod, "i");
        }
        
        $this->reWordBoundary=count($arr_sep)>0 ? '(?:'.implode('|', $arr_sep).')' : "";
        $this->reMod=implode("", $arr_mod);
    }
    
    #GustavMatch::init()#
    /**
     * Finds the intersections of the source file's properties and the search items and initializes the object's properties containing the found matches (`$matches`) and the match score (`$score`), as well as the source file's properties, HTML encoded and matching parts highlighted (`$highlight`).
     * Available properties are the source file's filename (`GustavBase::KEY_FILE`), title (if defined, `GustavBase::KEY_TITLE`) and tags (`GustavBase::KEY_TAGS`).
     *
     * When comparing the filename, this function compares case-sensitively. If the entire filename (incl. extension) is found in the items, the score is 2,
     * if only the filename with the extension stripped away is found, the score is 1.
     * The filename is matched against the last path segment of the source file's `_dest` GvBlock option.
     *
     * When comparing the source file's title, the items are searched in the title case-insensitively by default, or case-sensitively if the `GustavMatch::CASE_SENSITIVE` flag is set. Moreover the search items are wrapped into word-boundaries. For more information on *word-boundaries* see `GustavMatch::SPEC_LOW` and `GustavMatch::SPEC_HIGH`.
     * If the source file doesn't have a title, the score is 0. Otherwise the score is increased by 1 for each item for each occurrence within the title.
     * When searching for literal items in the title, this function acts a bit differently:
     * Additionally to the number of occurrences of the entire literal, for each of the literal's unique (case-insensitively) single items the product of the number of occurrences of the whole literal within the title and the number of occurrences of the single item within the literal is added to the score. These items are also added to the `$matches` array.
     * The single items are extracted by passing the literal to `GustavBase::getSearchTermItems()`.
     *
     * When comparing the source file's tags, the score is increased by 1 for each item found in the source file's tags (case-insensitively).
     *
     * When getting the score for the tags, the items are made unique case-insensitively before comparing them with the source file's properties. The same applies to a source file's title, with the only exception that the items are made unique ***case-sensitively*** when the `GustavMatch::CASE_SENSITIVE` flag is set.
     *
     * `$matches` Will contain the supported items (`GustavBase::KEY_FILE`, `GustavBase::KEY_TITLE`, `GustavBase::KEY_TAGS`) only, any other item is ignored.
     *
     * `$highlight` will contain the supported items only (see above). Moreover, items whose corresponding source file's property isn't set are removed. The properties' values are HTML encoded (if array, the array's items are) and matching parts are highlighted using `<mark>`. When highlighting titles, the same word-boundaries and treatment of the character case as for *matching* the title is used.
     *
     * @return void
     */
    private function init(){
        $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
        
        /*$src_a=$this->src;
        $str_path=$src_a->getPath();*/
        $arr_block=$this->src->getBlock()->get();
        $int_flags=$this->flags;
        
        /** /
        $this->score=0;
        $this->match=array();
        /*/
        $arr_matches=array();
        $int_score=0;
        /**/
        
        $arr_highlight=array(
            self::KEY_FILE=>preg_replace('/(?<=.)\/$/', "", rawurldecode(self::path2url($arr_block["_dest"], false))), //a trailing "/" that isn't the only character in the string is removed
            self::KEY_TAGS=>$arr_block["_tags"]
        );
        
        if(array_key_exists("_title", $arr_block)){
            $arr_highlight[self::KEY_TITLE]=$arr_block["_title"];
        }
        
        array_walk_recursive($arr_highlight, function(&$val) use ($str_hooks){
            if(is_string($val)){
                $val=call_user_func(array($str_hooks, "escapeHtml"), $val);
            }
        });
        
        foreach($this->search as $str_member=>$arr_items){
            $arr_matches_b=array();
            $mix_highlight=@$arr_highlight[$str_member];
            
            if($str_member==self::KEY_TITLE){ //title
                if(array_key_exists("_title", $arr_block)){
                    $str_title=$arr_block["_title"];
                    $re_wordBoundary=$this->reWordBoundary;
                    $str_reMod=$this->reMod;
                    
                    /**
                     * negative: closing tag,
                     * else (positive or 0): opening tag
                     *
                     * will always contain an even number of items.
                     *
                     * @type int[]
                     */
                    $arr_tagPos=array();
                    
                    foreach(self::arrayUnique($arr_items, !($int_flags&self::CASE_SENSITIVE)) as $val){ //lowercase strings (if GustavMatch::CASE_SENSITIVE flag isn't set) in $arr_items and make the items unique
                        if($int_flags&self::LITERAL_SPACES){
                            $re_val=self::escapeRegex($val);
                        }else{
                            $re_val=implode('\s+', array_map(array($str_hooks, "escapeRegex"), explode(" ", preg_replace('/\s+/', " ", $val))));
                        }
                        
                        $int_matches=(int)self::preg_match_all('/'.$re_wordBoundary.$re_val.$re_wordBoundary.'/'.$str_reMod, $str_title, $arr_a, PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
                        
                        if($int_matches>0){
                            /**
                             * falls hier eine literale wie `"eins"` ($val ist `eins` (string)) hinzugefuegt wird
                             * (z.b. 4 mal im titel vorkommt und somit auch 4 mal hinzugefuegt wird)
                             * kann diese nicht kleiner als die bereits vorhandene anzahl fuer das einfache item `eins` sein.
                             * zudem wird unten nochmal dieselbe anzahl hinzugefuegt
                             */
                            $arr_matches_b[$val]=$int_matches; //or 0, or 1 ?
                            /**
                             * nicht 0, weil:
                             *     der score waere dann genau so hoch als wenn man jedes enthaltene wort einfach als gewoehnliches item auf den gesamten titel
                             *     pruefen wuerde. die tatsache, dass die literale so wie sie im suchterm vorkommt auch genauso im titel vorkommt
                             *     (ohne gross-/kleinschreibung zu pruefen) wuerde so nicht extra gepunktet (falsch/sollte sie aber)
                             * nicht $int_matches, weil:
                             *     quasi ueberfluessig, weil der score der literale mit der [laenge der literale/anzahl der items in der literale] zunimmt und
                             *     die anzahl der (moeglichen) vorkommen der literale im titel abnimmt -> ausgleich
                             * 1, weil:
                             *     es scheint das beste ergebnis zu sein
                             *
                             * wirklich richtig, oder lieber $int_matches ?
                             *
                             * IMPORTANT! FINAL SOLUTION/REASON
                             * betrachte jdes item der literale folgendermaßen: füre das item so oft hinzu (score+=1) wie is im kontext der literale gefunden wurde (<anzahl_literale> * <anzahl_item_in_literale>). aber wirklich nur dann und nur für die items die auch in der literale vorkommen! dies erklärt warum man jedes einzelne item/wort einzeln hinzufuegt und somit das mehrfache hinzufuegen desselben wortes/items ([innerhalb/im kontext des] des gesamten titels) verhindet und nicht etwa einfach dasselbe item mehrfach hinzufuegt/zaehlt (in verscheidenen kontexten: gesamten titel, literale1, literale2) obwohl es tatsächlich nciht derartig oft im titel vorkommt sondern einfach dasselbe [wort/item/vorkommen (des items)] mehrfach hinzugefuegt wurde (was falsch ist).
                             * Um analog zu dem Verhalten von normalen items zu handeln wird die gesamte literale zusaetzlich als eigenes (einfaches) item behandelt und [genau wie ein solches/dementsprechend] so oft hinzugefügt wie es im gesamten titel gefunden wurde.
                             * -> $int_matches (nicht 1 oder 0!)
                             */
                            
                            /**
                             * Resulting array's string items will be unique (array_unique())
                             * (case-insensitively if the GustavMatch::CASE_SENSITIVE flag isn't set
                             * since $val would be lowercased in that case).
                             * If $val isn't a literal containing multiple items (separated by spaces),
                             * the resulting array will contain $val as its only item.
                             */
                            $arr_items_b=self::getSearchTermItems($val);
                            
                            foreach($arr_items_b as $val_b){
                                /**
                                 * If not a literal consisting of multiple items, the calculation to be executed will be `max( 1 * a, a )`
                                 * where `a` is the number of the occurrences of the item in the whole title.
                                 * Otherwise the part below would a bit more useful.
                                 *
                                 * replacing spaces with `\s+` in the item if the GustavMatch::LITERAL_SPACES flag isn't set isn't necessary
                                 * since `GustavBase::getSearchTermItems()` have been called on the literal which eliminates all spaces
                                 * and therefore the items won't contain any more.
                                 */
                                $arr_matches_b[$val_b]=max((int)self::preg_match_all('/'.$re_wordBoundary.self::escapeRegex($val_b).$re_wordBoundary.'/'.$str_reMod, $val)*$int_matches, (int)(@$arr_matches_b[$val_b]));
                            }
                            
                            foreach($arr_a as $val_b){
                                array_push($arr_tagPos, $val_b[0][1], -($val_b[0][1]+self::mb_strlen($val_b[0][0]))); //sigle items of a literal don't need to be highlighted separately since they are contained in the highlighted literal parts
                            }
                        }
                    }
                    
                    if(count($arr_tagPos)>0){
                        $mix_highlight="";
                        
                        usort($arr_tagPos, function($a, $b){
                            if(abs($a)==abs($b)){
                                /**
                                 * if empty matches would be possible it is important that `<mark></mark>` is used instead of `</mark><mark>`.
                                 * if the empty match would exist within another (not empty) match, `</mark><mark>` wouldn't be that terrible
                                 * since the closing tag would close the preceding opening tag and the opening tag would correspond to the
                                 * subsequent closing tag resulting in something like `<mark>abc</mark><mark>def</mark>`.
                                 * however, using `<mark></mark>` instead would work just as well. the example above would result in something
                                 * like `<mark>abc<mark></mark>def</mark>`.
                                 * if the empty match would *not* exist within another non-empty match, the second solution is required.
                                 * even the case below (where actually the connected tags belong together) would just work fine.
                                 *
                                 *     <mark>abcdef<mark></mark></mark>
                                 *       |           |       |     |
                                 *       `-----------|-------´     |
                                 *                   `-------------´
                                 *
                                 * while the first solution would not.
                                 *
                                 *     <mark>abcdef</mark></mark><mark>
                                 *       |            |      |     |
                                 *       `------------|------´     |
                                 *                    `------------´
                                 *
                                 * using the second solution ensures that all opening tags are closed again in some way.
                                 */
                                return $a>=0 ? -1 : 1; //or use `$a>$b` instead
                            }else{
                                return abs($a)<abs($b) ? -1 : 1;
                            }
                        });
                        
                        foreach($arr_tagPos as $i=>$val){
                            $int_a=$i==0 ? 0 : abs($arr_tagPos[$i-1]);
                            
                            $mix_highlight.=self::escapeHtml(self::mb_substr($str_title, $int_a, abs($val)-$int_a));
                            $mix_highlight.='<'.($val<0 ? "/" : "").'mark>';
                        }
                        $mix_highlight.=self::escapeHtml(self::mb_substr($str_title, abs($arr_tagPos[count($arr_tagPos)-1])));
                    }
                }
            }else if($str_member==self::KEY_TAGS){ //tags
                $arr_tags=$arr_block["_tags"]; //will never contain two items that differ in their letters' cases only ...
                $arr_tags_b=array_flip(array_map(array($str_hooks, "mb_strtolower"), $arr_tags)); //... therefore no items/keys will collide when flipping the lowercased array
                
                foreach(self::arrayUnique($arr_items, true) as $val){ //lowercase strings in $arr_items and make the items unique
                    if(array_key_exists($val, $arr_tags_b)){
                        $arr_matches_b[$val]=1;
                        
                        $mix_highlight[$arr_tags_b[$val]]='<mark>'.$mix_highlight[$arr_tags_b[$val]].'</mark>';
                    }
                }
            }else if($str_member==self::KEY_FILE){ //file
                $str_file=basename($arr_block["_dest"]); //dest (last path segment of dirname or actual filename if not "index.*")
                $str_name=pathinfo($str_file, PATHINFO_FILENAME);
                $str_root="/";
                $arr_a=array_flip($arr_items);
                $q_file=array_key_exists($str_file, $arr_a);
                $q_name=(array_key_exists($str_name, $arr_a) && !self::strEndsWith($arr_block["_dest"], DIRECTORY_SEPARATOR));
                $q_root=($arr_block["_dest"]==DIRECTORY_SEPARATOR && /**/array_key_exists($str_root, $arr_a)/*/count(array_intersect_key($arr_a, array_flip(array(DIRECTORY_SEPARATOR, "/", "\\"))))>0/**/); //if `_dest : /`
                
                if($q_file || $q_name){
                    if($q_file){
                        $arr_matches_b[$str_file]=1;
                        
                        $mix_highlight='<mark>'.self::escapeHtml($str_file).'</mark>';
                    }else /** /if($q_name)/**/{
                        $arr_matches_b[$str_name]=1;
                        
                        $mix_highlight='<mark>'.self::escapeHtml($str_name).'</mark>'.self::escapeHtml(self::mb_substr($str_file, self::mb_strlen($str_name)));
                    }
                    
                    $mix_highlight=self::escapeHtml(rtrim(rawurldecode(self::path2url(dirname($arr_block["_dest"]), false)), "/")."/").$mix_highlight;
                }else if($q_root){
                    $arr_matches_b[$str_root]=1;
                    
                    $mix_highlight='<mark>'.$mix_highlight.'</mark>';
                }
            }else{
                continue;
            }
            
            $int_score+=array_sum($arr_matches_b);
            $arr_matches[$str_member]=array_keys($arr_matches_b);
            
            if(!is_null($mix_highlight)){
                $arr_highlight[$str_member]=$mix_highlight;
            }
        }
        
        $this->score=$int_score;
        $this->matches=$arr_matches;
        $this->highlight=$arr_highlight;
    }
    
    
    
    #getter-functions#
    
    #GustavMatch::__call()#
    /**
     * A "magic" overloading function that gets called when an object's non-reachable function is called.
     * This function is used to emulate global getter functions for some of the object's properties. The following getters are available:
     *
     * +   getSrc(): The `GustavSrc` object for the source file whose properties should be matched against the search items. (`$src` property).
     * +   getMatches(): The search items matching the source file's properties (`$matches` property).
     * +   getScore(): The match score calculated from the matching search items (`$score` property).
     * +   getHighlight(): The source file's searched properties, HTML encoded and matching parts highlighted using `<mark>` (`$highlight` property).
     *
     * If any other non-reachable function is called, a `BadMethodCallException` is thrown.
     *
     * @param string $function_name The name of the called function.
     * @param array  $arguments     The arguments passed to the called function.
     *
     * @return mixed
     */
    public function __call($str_fn, $arr_args){
        $str_getterPrefix="get";
        $arr_getters=array_flip(array_map(function($val) use ($str_getterPrefix){
            return $str_getterPrefix.ucfirst($val);
        }, array(
            "src",
            //"search",
            //"flags",
            "matches",
            "score",
            "highlight"
        )));
        
        if(array_key_exists($str_fn, $arr_getters)){
            return $this->{lcfirst(self::lstrip($str_fn, $str_getterPrefix))};
        }
        
        throw new BadMethodCallException("Method doesn't exist.");
    }
    
    
    
}

require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), @array_pop(explode("\\", constant(ltrim(__NAMESPACE__."\\".pathinfo(__FILE__, PATHINFO_FILENAME)."::HOOKS_CLASS", "\\")))).".php"));
require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), "GustavSrc.php"));
