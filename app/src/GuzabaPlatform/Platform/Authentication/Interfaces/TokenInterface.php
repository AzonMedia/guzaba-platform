<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Authentication\Interfaces;

interface TokenInterface
{    
    /**
     * generates new token on user login
     * @return Token
     */
    public function generate_new_token() : self;

    /**
     * updates token_expiration_time on every request
     * @return Token
     */
    public function update_token() : self;
}
