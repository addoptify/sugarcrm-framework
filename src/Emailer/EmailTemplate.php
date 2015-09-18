<?php

namespace DRI\SugarCRM\Emailer;

require_once 'modules/PdfManager/PdfManagerHelper.php';
require_once 'include/Sugarpdf/SugarpdfHelper.php';

/**
 * @author Emil Kilhage
 */
class EmailTemplate
{
    /**
     * @var string
     */
    private $templateId;

    /**
     * @var string
     */
    private $subjectCachePath;

    /**
     * @var string
     */
    private $bodyCachePath;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $body;

    /**
     * @var \Sugar_Smarty
     */
    private $ss;

    /**
     * @var array
     */
    private $data = array();

    /**
     * @param string $key
     * @param string $value
     */
    public function assign($key, $value)
    {
        if ($value instanceof \SugarBean) {
            $value = $this->parseBeanFields($value);
        }

        $this->data[$key] = $value;
    }

    /**
     * @param string $templateId
     */
    public function setTemplateId($templateId)
    {
        $this->templateId = $templateId;
    }

    /**
     * @return \EmailTemplate
     */
    public function getEmailTemplate()
    {
        return \BeanFactory::getBean('EmailTemplates', $this->templateId);
    }

    /**
     *
     */
    public function process()
    {
        $this->buildCache();
        $this->prepair();
        $this->parse();
    }

    /**
     *
     */
    private function buildCache()
    {
        $this->subjectCachePath = $this->getTemplateCacheFile('subject');
        $this->bodyCachePath = $this->getTemplateCacheFile('body');

        $emailTemplate = $this->getEmailTemplate();

        $emailTemplate->subject = from_html($emailTemplate->subject);
        $emailTemplate->body_html = from_html($emailTemplate->body_html);

        $emailTemplate->subject = trim($emailTemplate->subject);
        $emailTemplate->body_html = trim($emailTemplate->body_html);

        sugar_file_put_contents($this->subjectCachePath, $emailTemplate->subject);
        sugar_file_put_contents($this->bodyCachePath, $emailTemplate->body_html);
    }

    /**
     * @param \SugarBean $bean
     *
     * @return array
     */
    public function parseBeanFields(\SugarBean $bean)
    {
        $fields = \PdfManagerHelper::parseBeanFields($bean, true);

        $fields = $this->formatDates($bean, $fields);

        return $fields;
    }

    /**
     * @param \SugarBean $bean
     * @param array $fields
     *
     * @return array
     */
    private function formatDates(\SugarBean $bean, array $fields)
    {
        $timeDate = \TimeDate::getInstance();
        foreach ($fields as $name => $value) {
            $def = $bean->getFieldDefinition($name);

            if (empty($def['type'])) {
                continue;
            }

            switch ($def['type']) {
                case 'date':
                    if (!empty($value)) {
                        $value = $timeDate->fromDbDate($value);
                        if ($value instanceof \DateTime) {
                            $fields[$name] = $timeDate->asUserDate($value);
                        }
                    }
                    break;
                case 'datetime':
                case 'datetimecombo':
                    if (!empty($value)) {
                        $value = $timeDate->fromDb($value);
                        if ($value instanceof \DateTime) {
                            $fields[$name] = $timeDate->asUser($value);
                        }
                    }
                    break;
                default:
                    break;
            }
        }

        return $fields;
    }

    /**
     *
     */
    private function prepair()
    {
        $this->ss = new \Sugar_Smarty();
        $this->ss->assign($this->data);
        $this->ss->assign('MOD', $GLOBALS['mod_strings']);
        $this->ss->assign('APP', $GLOBALS['app_strings']);
    }

    /**
     *
     */
    private function parse()
    {
        $this->subject = $this->ss->fetch($this->subjectCachePath);
        $this->body = $this->ss->fetch($this->bodyCachePath);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    private function getTemplateCacheFile($name)
    {
        return sugar_cached(
            sprintf(
                'modules/EmailTemplates/%s.%s.html',
                $this->templateId,
                $name
            )
        );
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
}
