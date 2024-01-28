<?php

namespace App\Doctrine\Listener\Screen;

use App\Business\ScreenBusiness;
use App\Entity\Screen;

class QRCodeKeyGeneratorListener
{
    public function __construct(
        private readonly ScreenBusiness $screenBusiness
    )
    {

    }

    public function prePersist(Screen $screen): void
    {
        if (null !== $screen->getQrCodeKey()) {
            return;
        }

        $screen->setQrCodeKey($this->screenBusiness->generateQRCodeKey());
    }
}