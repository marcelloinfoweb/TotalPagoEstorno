<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Funarbe\TotalPagoEstorno\Plugin\Backend\Magento\Sales\Model\Order;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order\Invoice;

class InvoicePlugin
{
    protected OrderRepositoryInterface $orderRepository;

    /**
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     */
    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Exception
     */
    public function afterRegister(Invoice $subject, $result)
    {
        $order_id = $result->getOrderId();
        $order = $this->orderRepository->get($order_id);

        $statusHistoryItem = $order->getStatusHistoryCollection()->getLastItem();
        //$status = $statusHistoryItem->getStatusLabel();
        $comment = $statusHistoryItem->getComment();

        try {

            if (strpos($comment, 'Authorized amount of') === true) {

                preg_match_all('/R\$(.*?)\./', $comment, $matches);
                $valor = str_replace(' ', '', $matches[1][0]);

                $order->setSubtotal($valor);
                $order->setBaseSubtotal($valor);
                $order->save();
            }

        } catch (\Exception $e) {
            $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/total-pago-estorno.log');
            $logger = new \Zend_Log();
            $logger->addWriter($writer);

            $logger->warn("Estorno erro: " . $e);
        }

        return $result;
    }
}
