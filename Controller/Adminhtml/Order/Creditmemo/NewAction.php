<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Funarbe\TotalPagoEstorno\Controller\Adminhtml\Order\Creditmemo;

use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Backend\App\Action;
use Magento\Sales\Api\OrderRepositoryInterface;

class NewAction extends \Magento\Backend\App\Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Magento_Sales::sales_creditmemo';

    /**
     * @var \Magento\Sales\Controller\Adminhtml\Order\CreditmemoLoader
     */
    protected \Magento\Sales\Controller\Adminhtml\Order\CreditmemoLoader $creditmemoLoader;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected \Magento\Framework\View\Result\PageFactory $resultPageFactory;

    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    protected \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory;
    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    private OrderRepositoryInterface $orderRepository;

    /**
     * @param Action\Context $context
     * @param \Magento\Sales\Controller\Adminhtml\Order\CreditmemoLoader $creditmemoLoader
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        Action\Context $context,
        \Magento\Sales\Controller\Adminhtml\Order\CreditmemoLoader $creditmemoLoader,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->creditmemoLoader = $creditmemoLoader;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->orderRepository = $orderRepository;
        parent::__construct($context);
    }

    /**
     * Creditmemo create page
     * @return \Magento\Backend\Model\View\Result\Forward|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $this->creditmemoLoader->setOrderId($this->getRequest()->getParam('order_id'));
        $this->creditmemoLoader->setCreditmemoId($this->getRequest()->getParam('creditmemo_id'));
        $this->creditmemoLoader->setCreditmemo($this->getRequest()->getParam('creditmemo'));
        $this->creditmemoLoader->setInvoiceId($this->getRequest()->getParam('invoice_id'));
        $creditmemo = $this->creditmemoLoader->load();
        $resultPage = $this->resultPageFactory->create();

        if ($creditmemo) {
            if ($comment = $this->_objectManager->get(\Magento\Backend\Model\Session::class)->getCommentText(true)) {
                $creditmemo->setCommentText($comment);
            }
            $resultPage->setActiveMenu('Magento_Sales::sales_order');
            $resultPage->getConfig()->getTitle()->prepend(__('Credit Memos'));

            if ($creditmemo->getInvoice()) {
                $resultPage->getConfig()->getTitle()->prepend(
                    __("New Memo for #%1", $creditmemo->getInvoice()->getIncrementId())
                );
            } else {
                $resultPage->getConfig()->getTitle()->prepend(__("New Memo"));

                $block = $resultPage->getLayout()->getBlock('adjustments');
                $block->setData('adjustment_custom', $this->getAdjustmentFeeCustom());
            }
            return $resultPage;
        }

        $resultForward = $this->resultForwardFactory->create();
        $resultForward->forward('noroute');

        return $resultForward;
    }

    public function getAdjustmentFeeCustom(): ?float
    {
        $AmountAuthorized = $this->getAmountAuthorizedCustom();


        $valor = $AmountAuthorized ?? 0.00;

        // TODO: Pegar a diferença dos dois valores com a taxa, somar com a taxa, o resultado subtrair com diferença dos dois valors com a taxa, o resultado subtrair a taxa, o resultado somar com o valor enviado para a Cielo
        $subtotal = $this->getSubtotalCustom();
        $taxa = $this->getTaxCustom();
        $frete = $this->getShippingAmountCustom();

        if ($taxa === 0.0) {
            return $valor;
        }

        $subtotalTaxa = $subtotal + $taxa;
        $diferenca = $subtotalTaxa - $valor;

        $diferencaMaisTaxa = $diferenca + $taxa;
        $diferencaMenosTax = $taxa - $diferenca;

        $valor += ($diferencaMaisTaxa - $diferencaMenosTax) - $taxa;

        return $valor;
    }

    public function getAmountAuthorizedCustom(): ?float
    {
        $order_id = $this->creditmemoLoader->getOrderId();
        $order = $this->orderRepository->get($order_id);
        return $order->getPayment()->getAmountAuthorized();
    }

    public function getSubtotalCustom(): ?float
    {
        $order_id = $this->creditmemoLoader->getOrderId();
        return $this->orderRepository->get($order_id)->getSubtotal();
    }

    public function getTaxCustom(): ?float
    {
        $order_id = $this->creditmemoLoader->getOrderId();
        return $this->orderRepository->get($order_id)->getTaxAmount();
    }

    public function getShippingAmountCustom(): ?float
    {
        $order_id = $this->creditmemoLoader->getOrderId();
        return $this->orderRepository->get($order_id)->getShippingAmount();
    }

}
