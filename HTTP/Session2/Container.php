<?php
//
// +-----------------------------------------------------------------------+
// | Copyright (c) 2004, Tony Bibbs                                        |
// | All rights reserved.                                                  |
// |                                                                       |
// | Redistribution and use in source and binary forms, with or without    |
// | modification, are permitted provided that the following conditions    |
// | are met:                                                              |
// |                                                                       |
// | o Redistributions of source code must retain the above copyright      |
// |   notice, this list of conditions and the following disclaimer.       |
// | o Redistributions in binary form must reproduce the above copyright   |
// |   notice, this list of conditions and the following disclaimer in the |
// |   documentation and/or other materials provided with the distribution.|
// | o The names of the authors may not be used to endorse or promote      |
// |   products derived from this software without specific prior written  |
// |   permission.                                                         |
// |                                                                       |
// | THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS   |
// | "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT     |
// | LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR |
// | A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT  |
// | OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, |
// | SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT      |
// | LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, |
// | DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY |
// | THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT   |
// | (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE |
// | OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.  |
// |                                                                       |
// +-----------------------------------------------------------------------+
// | Author: Alexander Radivanovich <info@wwwlab.net>                      |
// }         Tony Bibbs <tony@geeklog.net>                                 |
// +-----------------------------------------------------------------------+
//

require_once 'HTTP/Session2/ContainerInterface.php';

/**
 * Container class for storing session data data
 *
 * @author  Alexander Radivaniovich <info@wwwlab.net>
 * @package HTTP_Session2
 * @access  public
 */
abstract class HTTP_Session2_Container implements HTTP_Session2_Container_Interface
{
    /**
     * Additional options for the container object
     *
     * @var array
     * @access private
     */
    private $options = array();

    /**
     * Constrtuctor method
     *
     * @access public
     * @param  array  $options Additional options for the container object
     * @return void
     */
    public function __construct($options = null)
    {
        $this->setDefaults();
        if (is_array($options)) {
            $this->parseOptions();
        }
    }

    /**
     * Set some default options
     *
     * @access private
     */
    private function setDefaults()
    {
    }

    /**
     * Parse options passed to the container class
     *
     * @access protected
     * @param array Options
     */
    protected function parseOptions($options)
    {
    	foreach ($options as $option => $value) {
        	if (in_array($option, array_keys($this->options))) {
                $this->options[$option] = $value;
            }
        }
        print_r($this->options); exit;
    }

    /**
     * Set session save handler
     *
     * @access public
     * @return void
     */
    public function set()
    {
        $GLOBALS['HTTP_Session2_Container'] =& $this;
        session_module_name('user');
        session_set_save_handler(
            'HTTP_Session2_Open',
            'HTTP_Session2_Close',
            'HTTP_Session2_Read',
            'HTTP_Session2_Write',
            'HTTP_Session2_Destroy',
            'HTTP_Session2_GC'
        );
    }

}

// Delegate function calls to the object's methods
/** @ignore */
function HTTP_Session2_Open($save_path, $session_name) 
{ 
	return $GLOBALS['HTTP_Session2_Container']->open($save_path, $session_name); 
}

/** @ignore */
function HTTP_Session2_Close()                         
{ 
	return $GLOBALS['HTTP_Session2_Container']->close(); 
}

/** @ignore */
function HTTP_Session2_Read($id)                       
{ 
	return $GLOBALS['HTTP_Session2_Container']->read($id); 

}

/** @ignore */
function HTTP_Session2_Write($id, $data)               
{ 
	return $GLOBALS['HTTP_Session2_Container']->write($id, $data); 
}

/** @ignore */
function HTTP_Session2_Destroy($id)                    
{ 
	return $GLOBALS['HTTP_Session2_Container']->destroy($id); 
}

/** @ignore */
function HTTP_Session2_GC($maxlifetime)                
{ 
	return $GLOBALS['HTTP_Session2_Container']->gc($maxlifetime); 
}

session_set_save_handler('HTTP_Session2_Open',
	'HTTP_Session2_Close',
	'HTTP_Session2_Read',
	'HTTP_Session2_Write',
	'HTTP_Session2_Destroy',
	'HTTP_Session2_GC');
?>