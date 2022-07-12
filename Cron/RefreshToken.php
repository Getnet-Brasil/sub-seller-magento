<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

namespace Getnet\SubSellerMagento\Cron;

use Getnet\SubSellerMagento\Logger\Logger;
use Getnet\SubSellerMagento\Model\Console\Command\Administrative\Token;

/**
 * Class CronTab Refresh Token.
 */
class RefreshToken
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var Token
     */
    protected $token;

    /**
     * Constructor.
     *
     * @param Logger $logger
     * @param Token  $token
     */
    public function __construct(
        Logger $logger,
        Token $token
    ) {
        $this->logger = $logger;
        $this->token = $token;
    }

    /**
     * Execute the cron.
     *
     * @return void
     */
    public function execute()
    {
        $this->logger->info('Cronjob RefreshToken is executing.');
        $this->token->newToken();
        $this->logger->info('Cronjob RefreshToken is done.');
    }
}
