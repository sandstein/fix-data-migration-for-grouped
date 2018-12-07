<?php
/**
 * ConvertConfigurableAttributePlugin
 *
 * @author    Wilfried Wolf <wilfried.wolf@sandstein.de>
 * @copyright 2018 Sandstein Neue Medien GmbH. All rights reserved.
 * @license   https://opensource.org/licenses/MIT MIT Licence
 * @version   GIT: <GIT ID>
 *
 */

namespace Snm\FixDataMigrationForGrouped\Migration\Handler\EavAttribute;


use Migration\Exception;
use Migration\Handler\EavAttribute\ConvertConfigurableAttribute;
use Migration\ResourceModel\Record;

class ConvertConfigurableAttributePlugin
{

    /**
     * Since the ConvertConfigurableAttribute-Handler only applies to
     * the column "apply_to" and the field of the $subject is not accessible
     * it is hard coded here.
     *
     * @var string
     */
    protected $field = 'apply_to';

    /**
     * Corrects the handle for older Versions of the MigrationTool
     *
     * @param ConvertConfigurableAttribute $subject        the original class
     * @param callable                     $proceed        the original function
     * @param Record                       $recordToHandle the record from Magento 1
     * @param Record                       $oppositeRecord the record for Magento 2
     *
     * @throws Exception
     *
     * @return void
     */
    public function aroundHandle(
        ConvertConfigurableAttribute $subject,
        callable $proceed,
        Record $recordToHandle,
        Record $oppositeRecord
    ) {
        $subject->validate($recordToHandle);
        $sourceModel = $recordToHandle->getValue($this->field);
        $oppositeRecordValue = $oppositeRecord->getValue($this->field);

        // Treat case, where Magento 2 record is already defined and has values
        // (e.g. price)
        if (!empty($sourceModel) && !empty($oppositeRecordValue)) {
            $recordToHandle->setValue(
                $this->field,
                $this->_merge($sourceModel, $oppositeRecordValue)
            );
        } elseif (empty($sourceModel) && !empty($oppositeRecordValue)) {
            $recordToHandle->setValue(
                $this->field,
                $oppositeRecord->getValue($this->field)
            );
        } elseif (empty($sourceModel)
            || $recordToHandle->getValue('is_configurable')
        ) {
            $recordToHandle->setValue($this->field, null);
        }
    }

    /**
     * Merges the apply_to field of recordToHandle and oppositeRecord
     *
     * @param string $sourceModel
     * @param string $oppositeRecordValue
     *
     * @return string
     */
    private function _merge($sourceModel, $oppositeRecordValue)
    {
        return implode(
            ',',
            explode(',', $sourceModel) + explode(',', $oppositeRecordValue)
        );
    }
}