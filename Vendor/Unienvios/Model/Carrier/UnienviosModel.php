<?php

namespace Vendor\Unienvios\Model\Carrier;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Checkout\Model\Session as CheckoutSession;

/**
 * Custom shipping model
 */
class UnienviosModel extends AbstractCarrier implements CarrierInterface
{
    /**
     * @var string
     */
    protected $_code = 'unienvios';

    /**
     * @var bool
     */
    protected $_isFixed = true;

    /**
     * @var \Magento\Shipping\Model\Rate\ResultFactory
     */
    private $rateResultFactory;

    /**
     * @var \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
     */
    private $rateMethodFactory;

/**
* @var \Magento\Framework\HTTP\Client\Curl
*/
protected $_curl;
protected $_checkoutSession;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory

     * @param array $data
	* @param Magento\Framework\HTTP\Client\Curl $curl
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        array $data = [],
\Magento\Framework\HTTP\Client\Curl $curl,
CheckoutSession $checkoutSession

	
    ) {
$this->_curl = $curl;
$this->_checkoutSession = $checkoutSession;

        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);

        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
	
    }

    /**
     * Custom Shipping Rates Collector
     *
     * @param RateRequest $request
     * @return \Magento\Shipping\Model\Rate\Result|bool
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->rateResultFactory->create();

        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
        $method = $this->rateMethodFactory->create();

        $method->setCarrier($this->_code);
        $method->setCarrierTitle("Unienvios");

      /**  $method->setMethod($this->_code);
        $method->setMethodTitle($this->getConfigData('name'));

        $shippingCost = (float)$this->getConfigData('shipping_cost');

        $method->setPrice($shippingCost);
        $method->setCost($shippingCost);

        $result->append($method);
	**/
 $quotations=$this->getQuotation();
 $quotationsList = $quotations->quotations;


$method_select = $this->getCheckoutSession()->getQuote()->getShippingAddress()->getShippingMethod();

	foreach($quotationsList as $key=>$quot){
		$dado = [
		"code" => $quot->id,
		"title" =>$quot->name,
"description"=>"oioioi",
		"price" => $quot->final_price,
		"cost" => 0
		];
	$result->append($this->_getExpressShippingRate($dado));
	}
/**
	$dado1 = ["code"=>"915af911-5970-46b7-b4cb-3d637f7567ce", "title"=>"opa", "price"=>14.44, "cost"=>0];
	 $dado2 = ["code"=>"correiossedex", "title"=>"Correios SEDEX", "price"=>1.44, "cost"=>0]; 

	$result->append($this->_getExpressShippingRate($dado1));
$result->append($this->_getExpressShippingRate($dado2));  
**/
        return $result;
    }

	protected function _getExpressShippingRate($dado=null) {
   
    /* @var $rate Mage_Shipping_Model_Rate_Result_Method */
$method = $this->rateMethodFactory->create();
    $method->setCarrier($this->_code);
    $method->setCarrierTitle("Unienvios");
    $method->setMethod($dado["code"]);
    $method->setMethodTitle($dado["title"]);
    $method->setPrice($dado["price"]);
    $method->setCost($dado["cost"]);
    return $method;
}

    /**
     * @return array
     */
    public function getAllowedMethods()
    {
   //  $teste=$this->getQuotation();

	return array(
            "correiospac"=>"Correios PAC",
	    "correiossedex"=>"Correios Sedex"
   	 );
    }

    public function getQuotation() {
	$parans = [
	"zipcode_destiny"=>"07243170",
        "estimate_height"=>15,
	"estimate_width" => 15,
	"estimate_length"=> 15,
	"estimate_weight"=> 15,
	"order_value"=>150	
	];
	$parans = json_encode($parans);
	$this->_curl->addHeader("Content-Type", "application/json");
	$this->_curl->addHeader("email", "pcordista@gmail.com");
	$this->_curl->addHeader("password", "123456");
	$teste = $this->_curl->post("https://apihml.unienvios.com.br/external-integration/quotation/get-quotations", $parans); 
        $result =$this->_curl->getBody();
	$result = json_decode($result);
	
//	salvar token em variavel de session
$this->getCheckoutSession()->setMyTokenQuotation($result->token);
      return $result;
    }

public function getCheckoutSession() {
        return $this->_checkoutSession;
    }

}
