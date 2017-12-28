<?php
namespace GPIO\IO;

interface PinInterface
{

    const VALUE_HIGHT = 1;

    const VALUE_LOW = 0;

    const EDGE_NONE = 'none';

    const EDGE_BOTH = 'both';

    const EDGE_RISING = 'rising';

    const EDGE_FALLING = 'falling';

    const DIRECTION_IN = 'in';

    const DIRECTION_OUT = 'out';
}