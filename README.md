#GPIO 

##Usage

* Use the System functions like:

    <?php
    
    use \GPIO\GPIO\Kernel;
    
    Sysfs::export(13);
    
    Sysfs::direction(13,'in');
    
    $value = Sysfs::value(13);
    
* GPIO Interface:

    <?php
    
    use \GPIO;
    
    $gpio = new GPIO();
    
    $gpio->port(13)->direction('in');
    
    $value = $gpio->port(13)->get();
U can also use the buffer in the port requests GPIO::GPIO_USE_BUFFER


##Link

Documentation: [kernel.org](https://www.kernel.org/doc/Documentation/gpio/sysfs.txt "kernel.org")


v0.0