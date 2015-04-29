<?php

namespace DRI\SugarCRM\PDF;

require_once 'modules/PdfManager/PdfManagerHelper.php';
require_once 'include/Sugarpdf/sugarpdf/sugarpdf.pdfmanager.php';
require_once 'include/SugarPHPMailer.php';

use SugarBean;
use BeanFactory;
use SugarConfig;
use PdfManager;
use PdfManagerHelper;
use SugarpdfSmarty;

/**
 * @author Simon Köhlström
 */
class PDFTemplate extends SugarpdfSmarty
{
    /**
     * @var string
     */
    private $templateId;

    /**
     * @var string
     */
    private $pdfPath;

    /**
     * @var array
     */
    private $data = array();

    /**
     * Sets the bean to relate template to.
     *
     * @param SugarBean $bean
     */
    public function setBean(SugarBean $bean)
    {
        $this->bean = $bean;
    }

    /**
     * @return string
     */
    public function getPdfPath()
    {
        return $this->pdfPath;
    }

    /**
     * @param $key
     * @param $value
     */
    public function assign($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     *
     */
    public function preDisplay()
    {
        parent::preDisplay();

        /** @var PdfManager $pdfTemplate */
        $pdfTemplate = BeanFactory::getBean('PdfManager', $this->templateId);

        $this->templateLocation = $this->buildTemplateFile($pdfTemplate);

        $fields = PdfManagerHelper::parseBeanFields($this->bean, true);

        $this->ss->assign($this->data);
        $this->ss->assign('fields', $fields);
        $this->ss->assign('bean', $this->bean);

        $this->ss->fetch($this->templateLocation);
    }

    /**
     * @param string $templateId
     */
    public function setTemplateId($templateId)
    {
        $this->templateId = $templateId;
    }

    /**
     * Output the template to an email.
     *
     * @return string
     */
    public function Output()
    {
        $tmp = parent::Output('', 'S');
        $tmp = ltrim($tmp);

        $badOutput = ob_get_contents();

        if (strlen($badOutput) > 0) {
            ob_end_clean();
        }

        $dir = SugarConfig::getInstance()->get('upload_dir');
        $dir = rtrim($dir, '/');

        $this->pdfPath = $dir.'/'.create_guid().'.pdf';

        sugar_file_put_contents($this->pdfPath, $tmp);

        return $this->pdfPath;
    }

    /**
     *
     */
    public function deletePdf()
    {
        unlink($this->pdfPath);
    }

    /**
     * Builds the template and caches it.
     *
     * @param PdfManager $pdfTemplate
     *
     * @return string
     */
    private function buildTemplateFile(PdfManager $pdfTemplate)
    {
        if (!file_exists(sugar_cached('modules/PdfManager/tpls'))) {
            mkdir_recursive(sugar_cached('modules/PdfManager/tpls'));
        }

        $tpl_filename = sugar_cached('modules/PdfManager/tpls/'.$pdfTemplate->id.'.tpl');

        $pdfTemplate->body_html = from_html($pdfTemplate->body_html);

        sugar_file_put_contents($tpl_filename, $pdfTemplate->body_html);

        return $tpl_filename;
    }
}
