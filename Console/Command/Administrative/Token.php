<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Getnet\SubSellerMagento\Console\Command\Administrative;

use Getnet\SubSellerMagento\Model\Console\Command\Administrative\Token as ModelToken;
use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Token extends Command
{
    /**
     * Store Id.
     */
    public const STORE_ID = 'store_id';

    /**
     * @var ModelToken
     */
    protected $token;

    /**
     * @var State
     */
    protected $state;

    /**
     * @param State      $state
     * @param ModelToken $token
     */
    public function __construct(
        State $state,
        ModelToken $token
    ) {
        $this->state = $state;
        $this->token = $token;
        parent::__construct();
    }

    /**
     * Execute.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
        $this->token->setOutput($output);

        $storeId = $input->getArgument(self::STORE_ID);
        $this->token->newToken($storeId);
    }

    /**
     * Configure.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('getnet:sub_seller:administrative_token');
        $this->setDescription('Generate Administrative Token Manually');
        $this->setDefinition(
            [new InputArgument(self::STORE_ID, InputArgument::OPTIONAL, 'Store Id')]
        );
        parent::configure();
    }
}
