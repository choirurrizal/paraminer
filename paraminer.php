<?php
/*
paraminer - learn.hackersid.com
https://github.com/choirurrizal
*/
class paraminer
{
	private $filename;
	private static $options;
	private static $wordlist;
	private static $date;
	private static $valueOfParam;
	public function __construct($filename)
	{
		$this->filename = $filename;
		echo "\e[1;31;40m
               .-.
         __   /   \\   __
        (  `'.\\   /.'`  )
         '-._.\e[0m\e[1;33;40m(;;;)\e[0m\e[1;31;40m._.-'
         .-'  ,\e[0m\e[1;33;40m`\"`\e[0m\e[1;31;40m,  '-.
        (__.-'/   \\'-.__)\e[0m\e[1;32;40m/)_\e[0m
              \e[1;31;40m\\   /\e[0m\e[0;33;40m\\\e[0m    \e[1;32;40m/ / )\e[0m
               \e[1;31;40m'-'\e[0m  \e[0;33;40m|\e[0m   \e[1;32;40m\\/.-')\e[0m
               \e[1;32;40m,\e[0m    \e[0;33;40m| .'\e[0m\e[1;32;40m/\\'..)\e[0m
               \e[1;32;40m|\   \e[0m\e[0;33;40m|/\e[0m  \e[1;32;40m| \\_)\e[0m
               \e[1;32;40m\\ |\e[0m  \e[0;33;40m|\e[0m   \e[1;32;40m\\_/\e[0m
                \e[1;32;40m| \\\e[0m \e[0;33;40m/\e[0m
                 \e[1;32;40m\\|\e[0m\e[0;33;40m/\e[0m
             \e[1;32;40m\\\\\e[0m   \e[0;33;40m|\e[0m   \e[1;32;40m//\e[0m
        \e[1;35;40m^^^^^^^^^^^^^^^^^^^^^\e[0m
   \e[0;37;40mparaminer - learn.hackersid.com

 
    Help:
       -u URL (with protocol e.g. http/https)
       -w wordlist
    Example:
       $this->filename -u http://domain.com -w wordlist.txt\e[0m\n\n\n\n";
	}
	private static function __delay()
	{
		sleep(1);
	}
	private static function __try_post($_url,$_param,$_value,$_value2)
	{
		$ch = curl_init($_url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36");
		curl_setopt($ch, CURLOPT_TIMEOUT, 600);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array($_param => $_value));
		$sourceCode = curl_exec($ch);
		curl_close($ch);
		if(preg_match("/$_value/",$sourceCode)){
			echo "[\e[1;32;40mFOUND\e[0m] $_value2\r\n";
			echo "    Method => POST\n";
			echo "    Param  => $_param\n";
			echo "    PoC    => data:text/html;base64,".base64_encode("<form method=\"post\" action=\"$_url\"><input type=\"text\" name=\"$_param\" value=\"$_value\" /><button>run</button></form>")."\n\n";
		}
	}
	private static function __try_get($_url,$_param,$_value,$_value2)
	{
		$_url_parse = parse_url($_url);
		$_url_final = (isset($_url_parse['query']) ? "$_url&$_param=$_value" : (isset($_url_parse['path']) ? "$_url?$_param=$_value" : "$_url/?$_param=$_value"));
		$ch = curl_init($_url_final);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36");
		curl_setopt($ch, CURLOPT_TIMEOUT, 600);
		$sourceCode = curl_exec($ch);
		curl_close($ch);
		
		if(preg_match("/$_value/",$sourceCode)){
			echo "[\e[1;32;40mFOUND\e[0m] $_value2\r\n";
			echo "    Method => GET\n";
			echo "    Param  => $_param\n";
			echo "    PoC    => $_url_final\n\n";
		}
	}
	public static function __run()
	{
		self::$options = getopt("u:w:");
		self::$valueOfParam = "1l0v3y0u";
		if(isset(self::$options['u']) && isset(self::$options['w'])){
			self::$date = date("H:i:s Y-m-d");
			echo "[\e[0;32;40mINFO\e[0m] Starting at ".self::$date."\n";self::__delay();
			echo "[\e[0;32;40mINFO\e[0m] URL Target \e[1;37;40m\"".self::$options['u']."\"\e[0m\n";self::__delay();
			echo "[\e[0;32;40mINFO\e[0m] Wordlist File \e[1;37;40m\"".self::$options['w']."\"\e[0m\n";self::__delay();
			if(filter_var(self::$options['u'], FILTER_VALIDATE_URL)){
				if(file_exists(self::$options['w'])){
					self::$wordlist = explode("\r\n",file_get_contents(self::$options['w']));
					for($key = 0; $key <= count(self::$wordlist)-1 ; $key++){
						$spaces = ($key == 0 ? "" : substr(str_replace(str_split(self::$wordlist[$key-1]), " ", self::$wordlist[$key-1]),0,(strlen(self::$wordlist[$key-1]) > strlen(self::$wordlist[$key]) ? strlen(self::$wordlist[$key-1])-strlen(self::$wordlist[$key]) : 1)));
						echo "[\e[0;33;40mTESTING\e[0m] ".self::$wordlist[$key]."$spaces\r";
						self::__try_post(self::$options['u'],self::$wordlist[$key],self::$valueOfParam,str_pad(self::$wordlist[$key], strlen(self::$wordlist[$key])+3, " ").$spaces);
						self::__try_get(self::$options['u'],self::$wordlist[$key],self::$valueOfParam,str_pad(self::$wordlist[$key], strlen(self::$wordlist[$key])+3, " ").$spaces);
					}
					echo "           $spaces";
				}else{
					echo "[\e[1;31;40mFAILED\e[0m] Error opening wordlist file : \e[1;37;40m\"".self::$options['w']."\"\e[0m\n";
				}
			}else{
				echo "[\e[1;31;40mFAILED\e[0m] Not a valid URL format : \e[1;37;40m\"".self::$options['u']."\"\e[0m\n";
			}
			self::$date = date("H:i:s Y-m-d");
			echo "\r[\e[0;32;40mINFO\e[0m] Ending at ".self::$date."\n\n";
		}

	}
}
$paraminer = new paraminer($argv[0]);
paraminer::__run();
