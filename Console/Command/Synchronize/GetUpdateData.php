<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Getnet\SubSellerMagento\Console\Command\Synchronize;

use Getnet\SubSellerMagento\Model\Console\Command\Synchronize\GetUpdateData as ModelGetUpdateData;
use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Get Update Data seller data with Getnet.
 */
class GetUpdateData extends Command
{
    /**
     * @const string.
     */
    public const SUB_SELLER_ID = 'sub_seller_id';

    /**
     * @var ModelGetUpdateData
     */
    protected $getUpdateData;

    /**
     * @var State
     */
    protected $state;

    /**
     * @param State              $state
     * @param ModelGetUpdateData $getUpdateData
     */
    public function __construct(
        State $state,
        ModelGetUpdateData $getUpdateData
    ) {
        $this->state = $state;
        $this->getUpdateData = $getUpdateData;
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
        $this->getUpdateData->setOutput($output);

        $subSellerId = (int) $input->getArgument(self::SUB_SELLER_ID);
        $this->getUpdateData->getUpdateData($subSellerId);
    }

    /**
     * Configure.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('getnet:sub_seller:get_update_data');
        $this->setDescription('Get Update Data on Getnet');
        $this->setDefinition(
            [new InputArgument(self::SUB_SELLER_ID, InputArgument::REQUIRED, 'Seller Id')]
        );
        parent::configure();
    }
}
