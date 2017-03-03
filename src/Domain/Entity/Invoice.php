<?php
namespace CleanPhp\Invoicer\Domain\Entity;

class Invoice extends AbstractEntity
{
    /**
     * @var Order
     */
    protected $order;

    /**
     * @var \DateTime
     */
    protected $invoiceDate;

    /**
     * @var float
     */
    protected $total;

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder(Order $order)
    {
        $this->order = $order;
        return $this;
    }

    public function getInvoiceDate()
    {
        return $this->invoiceDate;
    }

    public function setInvoiceDate(\DateTime $invoiceDate)
    {
        $this->invoiceDate = $invoiceDate;
        return $this;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }
}
