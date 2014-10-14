<?php
namespace App\Controllers\Catalog;

use App\Controllers\BaseController;
use CategorySite;

class IndexController extends BaseController
{

	public $layout = 'catalog';

	public function index()
	{
		CategorySite::queryLogDelimiter('===== controller =====');

		$tree = CategorySite::treeList(['type', 'symlink', 'tags']);

		CategorySite::queryLogDelimiter('===== view =====');

		$this->make('index', [
			'tree' => $tree,
		]);
	}

	public function tags($path = null)
	{
		CategorySite::queryLogDelimiter('===== controller =====');

		$tags = CategorySite::tagList();
		$tree = [];
		$id = null;
		if ($path) {
			$tag = CategorySite::tagByPath($path);
			$id = $tag->id;
			$tree = CategorySite::treeByTag($tag);
		}

		CategorySite::queryLogDelimiter('===== view =====');

		$this->make('tags', [
			'tags' => $tags,
			'tree' => $tree,
			'id' => $id,
		]);
	}

	public function types($path = null)
	{
		CategorySite::queryLogDelimiter('===== controller =====');

		$types = CategorySite::typeList();
		$tree = [];
		$id = 0;
		if ($path) {
			$type = CategorySite::typeByPath($path);
			$id = $type->id;
			$tree = CategorySite::treeByType($type);
		}

		CategorySite::queryLogDelimiter('===== view =====');

		$this->make('types', [
			'types' => $types,
			'tree'  => $tree,
			'id' => $id,
		]);
	}

	public function margins($id = null)
	{
		CategorySite::queryLogDelimiter('===== controller =====');

		$tree = CategorySite::treeList();
		$treeMargins = [];
		$treeParents = [];
		$neighbors = [];
		if ($id) {
			CategorySite::init($id);
			$treeMargins = CategorySite::treeByItem();
			$treeParents = CategorySite::parents();
			$neighbors = CategorySite::neighbors();
		}

		CategorySite::queryLogDelimiter('===== view =====');

		$this->make('margins', [
			'tree'        => $tree,
			'treeMargins' => $treeMargins,
			'treeParents' => $treeParents,
			'neighbors'   => $neighbors,
			'id'          => $id,
		]);
	}

} 