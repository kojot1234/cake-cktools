<?php
namespace CkTools\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SystemContents Model
 */
class SystemContentsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('system_contents');
        $this->displayField('id');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');

        $this->addBehavior('Translate', ['fields' => [
            'title',
            'content'
        ]]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'uuid'])
            ->allowEmpty('id', 'create')
            ->requirePresence('identifier', 'create')
            ->notEmpty('identifier')
            ->allowEmpty('notes');
        return $validator;
    }

    /**
     * Find a content by its string identifier
     *
     * @param string $identifier identifier to look for
     * @param string $locale will override the locale of TranslateBehavior temporarily
     * @return SystemContent
     */
    public function getByIdentifier($identifier, $locale = null)
    {
        if ($locale) {
            $oldLocale = $this->locale();
            $this->locale($locale);
        }

        $res = $this->find()->where([
            'identifier' => $identifier
        ])->first();

        if ($locale) {
            $this->locale($oldLocale);
        }

        return $res;
    }
}
