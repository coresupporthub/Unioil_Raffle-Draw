<?php

namespace App\Policies;

use Spatie\Csp\Directive;
use Spatie\Csp\Policies\Basic;
use Spatie\Csp\Keyword;
class ContentSecurityPolicy extends Basic
{
    public function configure()
    {
        parent::configure();
        $this->addDirective(Directive::SCRIPT, 'https://ajax.googleapis.com')
             ->addDirective(Directive::SCRIPT, 'https://cdnjs.cloudflare.com')
             ->addDirective(Directive::SCRIPT, 'https://cdn.jsdelivr.net')
             ->addDirective(Directive::SCRIPT, 'https://cdn.datatables.net')

             ->addDirective(Directive::STYLE, 'https://cdnjs.cloudflare.com')
             ->addDirective(Directive::STYLE, 'https://cdn.jsdelivr.net')
             ->addDirective(Directive::STYLE, 'https://cdn.datatables.net')
             ->addDirective(Directive::STYLE, 'https://rsms.me')

             ->addDirective(Directive::IMG, 'https://cdn.jsdelivr.net')
             ->addDirective(Directive::IMG, 'data:')

             ->addDirective(Directive::SCRIPT, 'https://cdnjs.cloudflare.com')
             ->addDirective(Directive::SCRIPT, 'https://fonts.googleapis.com')
             ->addDirective(Directive::SCRIPT, 'https://rsms.me')
             ->addDirective(Directive::SCRIPT, 'https://cdn.jsdelivr.net')
             ->addDirective(Directive::FONT, [
                Keyword::SELF,
                'https://cdnjs.cloudflare.com',
            ])
             ->addNonceForDirective(Directive::SCRIPT)
             ->addNonceForDirective(Directive::STYLE);
    }
}
