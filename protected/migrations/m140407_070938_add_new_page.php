<?php

class m140407_070938_add_new_page extends CDbMigration
{
	public function up()
	{
		$this->execute("INSERT INTO tbl_pages (page_name, page_title, page_content)
		 VALUES ('videoInstruction', 'Инструкция', '<p style=\"text-align:center;\">
				<iframe width=\"728\" height=\"420\" src=\"//www.youtube.com/embed/MA7K12JcrEM?rel=0\\\" frameborder=\"0\" style=\"font-size:10pt;\">
				</iframe>
				</p>'
				)
		 ");
	}

	public function down()
	{
		echo "m140407_070938_add_new_page does not support migration down.\n";

		return false;
	}

}