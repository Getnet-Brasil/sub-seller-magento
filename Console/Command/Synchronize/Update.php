<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Getnet\SubSellerMagento\Console\Command\Synchronize;

use Getnet\SubSellerMagento\Model\Console\Command\Synchronize\Update as ModelUpdate;
use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Update seller data with Getnet.
 */
class Update extends Command
{
    /**
     * @const string.
     */
    public const SUB_SELLER_ID = 'sub_seller_id';

    /**
     * @var ModelUpdate
     */
    protected $update;

    /**
     * @var State
     */
    protected $state;

    /**
     * @param State       $state
     * @param ModelUpdate $update
     */
    public function __construct(
        State $state,
        ModelUpdate $update
    ) {
        $this->state = $state;
        $this->update = $update;
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
        $this->update->setOutput($output);

        $subSellerId = (int) $input->getArgument(self::SUB_SELLER_ID);
        $this->update->update($subSellerId);
    }

    /**
     * Configure.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('getnet:sub_seller:update');
        $this->setDescription('Update Sub Seller on Getnet');
        $this->setDefinition(
            [new InputArgument(self::SUB_SELLER_ID, InputArgument::REQUIRED, 'Seller Id')]
        );
        parent::configure();
    }
}
