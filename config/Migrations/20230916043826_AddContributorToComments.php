<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddContributorToComments extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('comments');
        $table->addColumn('contributor', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => false,
        ]);
        $table->update();
    }
}
