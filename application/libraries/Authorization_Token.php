<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*   Authorization_Token
* -------------------------------------------------------------------
* API Token Check and Generate
*
* @author: Jeevan Lal
* @version: 0.0.5
*/
require_once APPPATH . 'third_party/php-jwt/JWT.php';
require_once APPPATH . 'third_party/php-jwt/BeforeValidException.php';
require_once APPPATH . 'third_party/php-jwt/ExpiredException.php';
require_once APPPATH . 'third_party/php-jwt/SignatureInvalidException.php';
use \Firebase\JWT\JWT;
class Authorization_Token 
{
    /**
     * Token Key
     */
    protected $token_key;
    /**
     * Token algorithm
     */
    protected $token_algorithm;
    /**
     * Request Header Name
     */
    protected $token_header = ['authorization','Authorization'];
    /**
     * Token Expire Time
     * ----------------------
     * ( 1 Day ) : 60 * 60 * 24 = 86400
     * ( 1 Hour ) : 60 * 60     = 3600
     * ( 1 Week ) : 60 * 60 * 24 * 7 = 604800
     * ( 1 Month ) : 60 * 60 * 24 * 30 = 2592000
     * ( 1 Year ) : 60 * 60 * 24 * 365 = 31536000
     */
    protected $token_expire_time = 604800; 
    public function __construct()
	{
        $this->CI =& get_instance();
        /** 
         * jwt config file load
         */
        $this->CI->load->config('jwt');
        /**
         * Load Config Items Values 
         */
        $this->token_key        = $this->CI->config->item('jwt_key');
        $this->token_algorithm  = $this->CI->config->item('jwt_algorithm');
    }
    /**
     * Generate Token
     * @param: user data
     */
    public function generateToken($data)
    {
        try {
            return JWT::encode($data, $this->token_key, $this->token_algorithm);
        }
        catch(Exception $e) {
            return 'Mensaje: ' .$e->getMessage();
        }
    }
    /**
     * Validate Token with Header
     * @return : user informations
     */
    public function validateToken()
    {
        /**
         * Request All Headers
         */
        $headers = $this->CI->input->request_headers();
        
        /**
         * Authorization Header Exists
         */
        $token_data = $this->tokenIsExist($headers);
        if($token_data['status'] === TRUE)
        {
            try
            {
                /**
                 * Token Decode
                 */
                try {
                    $token_decode = JWT::decode($headers[$token_data['key']], $this->token_key, array($this->token_algorithm));
                }
                catch(Exception $e) {
                    return ['status' => FALSE, 'message' => $e->getMessage()];
                }
                if(!empty($token_decode) AND is_object($token_decode))
                {
                    // Check User ID (exists and numeric)
                    if(empty($token_decode->id) OR !is_numeric($token_decode->id)) 
                    {
                        return ['status' => FALSE, 'message' => 'ID Usuario sin definir!'];
                    // Check Token Time
                    }else if(empty($token_decode->time OR !is_numeric($token_decode->time))) {
                        
                        return ['status' => FALSE, 'message' => 'Token, tiempo sin definir!'];
                    }
                    else
                    {
                        /**
                         * Check Token Time Valid 
                         */
                        $time_difference = strtotime('now') - $token_decode->time;
                        if( $time_difference >= $this->token_expire_time )
                        {
                            return ['status' => FALSE, 'message' => 'Token Expiró.'];
                        }else
                        {
                            /**
                             * All Validation False Return Data
                             */
                            return ['status' => TRUE, 'data' => $token_decode];
                        }
                    }
                    
                }else{
                    return ['status' => FALSE, 'message' => 'Denegado'];
                }
            }
            catch(Exception $e) {
                return ['status' => FALSE, 'message' => $e->getMessage()];
            }
        }else
        {
            // Authorization Header Not Found!
            return ['status' => FALSE, 'message' => $token_data['message'] ];
        }
    }
    /**
     * Validate Token with POST Request
     */
    public function validateTokenPost()
    {
        if(isset($_POST['token']))
        {
            $token = $this->CI->input->post('token', TRUE);
            if(!empty($token) AND is_string($token) AND !is_array($token))
            {
                try
                {
                    /**
                     * Token Decode
                     */
                    try {
                        $token_decode = JWT::decode($token, $this->token_key, array($this->token_algorithm));
                    }
                    catch(Exception $e) {
                        return ['status' => FALSE, 'message' => $e->getMessage()];
                    }
    
                    if(!empty($token_decode) AND is_object($token_decode))
                    {
                        // Check User ID (exists and numeric)
                        if(empty($token_decode->id) OR !is_numeric($token_decode->id)) 
                        {
                            return ['status' => FALSE, 'message' => 'ID Usuario sin definir!'];
    
                        // Check Token Time
                        }else if(empty($token_decode->time OR !is_numeric($token_decode->time))) {
                            
                            return ['status' => FALSE, 'message' => 'Token, tiempo sin definir!'];
                        }
                        else
                        {
                            /**
                             * Check Token Time Valid 
                             */
                            $time_difference = strtotime('now') - $token_decode->time;
                            if( $time_difference >= $this->token_expire_time )
                            {
                                return ['status' => FALSE, 'message' => 'Token Expiró.'];
    
                            }else
                            {
                                /**
                                 * All Validation False Return Data
                                 */
                                return ['status' => TRUE, 'data' => $token_decode];
                            }
                        }
                        
                    }else{
                        return ['status' => FALSE, 'message' => 'Denegado'];
                    }
                }
                catch(Exception $e) {
                    return ['status' => FALSE, 'message' => $e->getMessage()];
                }
            }else
            {
                return ['status' => FALSE, 'message' => 'Token no esta definido.' ];
            }
        } else {
            return ['status' => FALSE, 'message' => 'Token no esta definido.'];
        }
    }
    /**
     * Token Header Check
     * @param: request headers
     */
    public function tokenIsExist($headers)
    {
        if(!empty($headers) AND is_array($headers)) {
            foreach ($this->token_header as $key) {
                if (array_key_exists($key, $headers) AND !empty($key))
                    return ['status' => TRUE, 'key' => $key];
            }
        }
        return ['status' => FALSE, 'message' => 'Token no esta definido.'];
    }
    /**
     * Fetch User Data
     * -----------------
     * @param: token
     * @return: user_data
     */
    public function userData()
    {
        /**
         * Request All Headers
         */
        $headers = $this->CI->input->request_headers();
        /**
         * Authorization Header Exists
         */
        $token_data = $this->tokenIsExist($headers);
        if($token_data['status'] === TRUE)
        {
            try
            {
                /**
                 * Token Decode
                 */
                try {
                    $token_decode = JWT::decode($headers[$token_data['key']], $this->token_key, array($this->token_algorithm));
                }
                catch(Exception $e) {
                    return ['status' => FALSE, 'message' => $e->getMessage()];
                }
                if(!empty($token_decode) AND is_object($token_decode))
                {
                    return $token_decode;
                }else{
                    return ['status' => FALSE, 'message' => 'Denegado'];
                }
            }
            catch(Exception $e) {
                return ['status' => FALSE, 'message' => $e->getMessage()];
            }
        }else
        {
            // Authorization Header Not Found!
            return ['status' => FALSE, 'message' => $token_data['message'] ];
        }
    }
}