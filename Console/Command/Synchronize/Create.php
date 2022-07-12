<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Getnet\SubSellerMagento\Console\Command\Synchronize;

use Getnet\SubSellerMagento\Model\Console\Command\Synchronize\Create as ModelCreate;
use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create sub seller with Getnet.
 */
class Create extends Command
{
    /**
     * @const string.
     */
    public const SUB_SELLER_ID = 'sub_seller_id';

    /**
     * @var ModelCreate
     */
    protected $create;

    /**
     * @var State
     */
    protected $state;

    /**
     * @param State       $state
     * @param ModelCreate $create
     */
    public function __construct(
        State $state,
        ModelCreate $create
    ) {
        $this->state = $state;
        $this->create = $create;
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
        $this->create->setOutput($output);

        $subSellerId = (int) $input->getArgument(self::SUB_SELLER_ID);
        $this->create->create($subSellerId);
    }

    /**
     * Configure.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('getnet:sub_seller:create');
        $this->setDescription('Create Sub Seller on Getnet');
        $this->setDefinition(
            [new InputArgument(self::SUB_SELLER_ID, InputArgument::REQUIRED, 'Seller Id')]
        );
        parent::configure();
    }
}
