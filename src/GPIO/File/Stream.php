<?php
/**
 * GPIO\File namespace
 */
namespace GPIO\File;

/**
 * Namespace imports
 */
use GPIO\Exception\FileException;

/**
 * Use this class to access the gpio files.
 * This class uses the streamwrapper like fopen.
 *
 * @author raah
 * @see http://php.net/manual/en/class.streamwrapper.php
 */
class Stream
{

    /**
     * Set this flag to define the stream
     * as a write-only stream.
     *
     * @var integer
     */
    const FLAG_STREAM_WRITE = 1;

    /**
     * Set this flag to define the stream
     * as a read-only stream.
     *
     * @var integer
     */
    const FLAG_STREAM_READ = 2;

    /**
     * Use this flag to open a context
     * and block them.
     *
     * @var integer
     */
    const FLAG_STREAM_BLOCK = 4;

    /**
     *
     * @var boolean
     */
    private $isblocked = false;

    /**
     * Stores the mainly gpio file base.
     * Use setBase() to set it.
     *
     * @example $base.'/'.$context
     *         
     * @var string
     */
    protected $base = '/sys/class/gpio';

    /**
     * Contains the stream mode,
     * currently supported: r/w/w+.
     * Use the flags to set the mode,
     * NOTICE: Open a stream without
     * read/write flag for the w+ mode.
     *
     * @var string
     */
    private $mode = '';

    /**
     * streamWrapper object.
     *
     * @var object \streamWrapper
     */
    private $stream = null;

    /**
     * Collect errors in case thats
     * fwrite or fread failed.
     * 
     * @var array
     */
    protected $streamerrors = [];

    /**
     * Constructor.
     * Call GPIO\File\Stream::open() in
     * case that the $context is not empty.
     *
     * @param string $context
     *            Set the stream context.
     *            NOTICE: the default basepath is '/sys/class/gpio'.
     *            The real filename is $base.'/'.$context!
     * @param number $flags
     *            See the class consts, use them optional.
     */
    public function __construct($context, $flags)
    {
        if ($context) {
            
            $this->open($context, $flags);
        }
    }

    /**
     * Destructor.
     * Calls the GPIO\File\Stream::close()
     * function.
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * ToString:
     * Returns the content from a open stream
     * object.
     *
     * @example "$stream = new Stream($context);
     *          $value = (string) $stream;"
     *         
     * @return string Return the GPIO\File\Stream::read()
     *         function and closes the stream.
     */
    public function __toString()
    {
        if ($this->stream) {
            
            return $this->read(true);
        }
    }

    /**
     * DebugInfo:
     * use the with var_dump to get more informations.
     *
     * @return array Some helpful debug informations.
     */
    public function __debugInfo()
    {
        return [
            'stream' => $this->stream,
            'mode' => $this->mode,
            'base' => $this->base,
            'isBlocked' => $this->isblocked
        ];
    }

    /**
     * Unuseful magic methods.
     */
    public function __clone()
    {}

    public function __callstatic()
    {}

    /**
     *
     * @param string $context
     *            Set the stream context.
     *            NOTICE: the default basepath is '/sys/class/gpio'.
     *            The real filename is $base.'/'.$context!
     * @param number $flags
     *            See the class consts, use them optional.
     * @throws FileException
     */
    public function open($context, $flags = 0)
    {
        if (! $context) {
            
            throw new FileException("Missing context in file stream.");
        }
        
        if ($flags & self::FLAG_STREAM_READ) {
            
            $this->mode = 'r';
        } elseif ($flags & self::FLAG_STREAM_WRITE) {
            
            $this->mode = 'w';
        } else {
            
            $this->mode = 'w+';
        }
        
        $file = $this->buildFilePath($context);
        
        if (! is_readable($file)) {
            
            throw new FileException("Cant access to: " . $file);
        }
        
        /**
         * include base = flase
         */
        $this->stream = fopen($file, $this->mode, false);
        
        if ($flags & self::FLAG_STREAM_BLOCK) {
            
            $this->block();
        }
    }

    /**
     * This function unblocks the stream
     * and close them.
     *
     * NOTICE: This resets the mode!
     */
    public function close()
    {
        if ($this->stream) {
            
            if ($this->isblocked) {
                
                $this->unblock();
            }
            
            fclose($this->stream);
            
            if ($this->stream) {
                
                $this->stream = null;
            }
            
            $this->mode = '';
        }
    }

    /**
     * Gets content from the stream
     * object.
     *
     * @param boolean $close
     *            Use this to close the stream immidiatly after reading.
     * @throws FileException
     * @return void|string Gets stream content.
     */
    public function read($close = false)
    {
        if ($this->isblocked) {
            
            return;
        }
        
        if ($this->stream) {
            
            if ($this->mode == 'w') {
                
                throw new FileException("Try to write something to a write-only stream.");
            }
            
            if ($buffer = fread($this->stream) === false) {
                
                /**
                 * reads __debugInfo
                 */
                $this->newStreamError();
            }
            
            if ($close) {
                
                $this->close();
            }
            
            return $buffer;
        }
    }

    /**
     * Puts content to the stream
     * object.
     *
     * @param string $content
     *            Writen this string to the stream.
     * @param boolean $close
     *            Use this to close the stream immidiatly after writing.
     * @throws FileException
     */
    public function write($content = '', $close = false)
    {
        if ($this->isblocked) {
            
            return;
        }
        
        if ($this->stream) {
            
            if ($this->mode == 'r') {
                
                throw new FileException("Try to write something to a read-only stream.");
            }
            
            if (fwrite($this->stream, $content) === false) {
                
                /**
                 * reads __debugInfo
                 */
                $this->newStreamError();
            }
            
            if ($close) {
                
                $this->close();
            }
        }
    }

    /**
     * Block the stream context.
     *
     * @see http://php.net/manual/en/function.stream-set-blocking.php
     */
    public function block()
    {
        if ($this->stream) {
            
            stream_set_blocking($this->stream, true);
            
            $this->isblocked = true;
        }
    }

    /**
     * Unblocking the stream context.
     *
     * @see http://php.net/manual/en/function.stream-set-blocking.php
     */
    public function unblock()
    {
        if ($this->stream) {
            
            stream_set_blocking($this->stream, false);
            
            $this->isblocked = false;
        }
    }

    /**
     * Builds the $context filename by
     * adding the base and path seperators.
     *
     * @param string $context
     *            Stream context without base part.
     * @return string Returns filename.
     */
    protected function buildFilePath($context)
    {
        if (strpos($context, '/') != 0) {
            
            return $this->base . '/' . $context;
        } else {
            
            return $this->base . $context;
        }
    }

    
    /**
     * Puts the debug infos
     * in a array.
     * 
     * @see \GPIO\File\Stream::__debugInfo()
     */
    protected function newStreamError()
    {
        $this->streamerrors[] = $this->__debugInfo();
    }

    /**
     * Set a new base.
     * NOTICE: This do not changes the
     * base from a open stream object!
     *
     * @param string $base
     *            New base Path like '/home/gpio'.
     * @throws FileException
     */
    public function setBase($base)
    {
        if (! $base) {
            
            return;
        }
        
        if (substr($base, - 1) == '/') {
            
            $base = substr($base, 0, - 1);
        }
        
        if (! is_dir($base)) {
            
            throw new FileException("Cant find new base: " . $base);
        }
        
        $this->base = $base;
    }

    /**
     * Returns the last stream error.
     *
     * @return array
     */
    public function getLastError()
    {
        return $this->streamerrors[max($this->streamerrors)];
    }

    /**
     * Returns all stream errors.
     *
     * @return array
     */
    public function getAllErrors()
    {
        return $this->streamerrors;
    }

    /**
     * Returns the current base.
     *
     * @return string self::$base
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * Returns the current mode.
     *
     * @return string self::$mode
     */
    public function getMode()
    {
        return $this->mode;
    }
}