<?php
/**
 * @filesource index/controllers/category.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Category;

use \Kotchasan\Http\Request;
use \Kotchasan\Login;
use \Kotchasan\Html;
use \Kotchasan\Language;

/**
 * module=category
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{

  /**
   * แก้ไขหมวดหมู่
   *
   * @param Request $request
   * @return string
   */
  public function render(Request $request)
  {
    // สมาชิก
    if ($login = Login::isMember()) {
      $typies = array(
        1 => 'Income type',
        2 => 'Expense type',
        4 => 'Wallet',
      );
      // 1=หมวดหมู่, 4=กระเป๋าเงิน
      $typ = $request->request('typ')->toInt();
      $typ = isset($typies[$typ]) ? $typ : 4;
      // ข้อความ title bar
      $this->title = Language::get($typies[$typ]);
      // เลือกเมนู
      $this->menu = 'tools';
      // แสดงผล
      $section = Html::create('section');
      // breadcrumbs
      $breadcrumbs = $section->add('div', array(
        'class' => 'breadcrumbs'
      ));
      $ul = $breadcrumbs->add('ul');
      $ul->appendChild('<li><a class="icon-home" href="index.php">{LNG_Home}</a></li>');
      $ul->appendChild('<li><span>{LNG_Tools}</span></li>');
      $section->add('header', array(
        'innerHTML' => '<h2 class="icon-'.($typ == 4 ? 'wallet' : 'category').'">'.$this->title.'</h2>'
      ));
      $section->add('a', array(
        'id' => "ierecord",
        'href' => WEB_URL.'index.php?module=ierecord',
        'title' => "{LNG_Recording} {LNG_Income}/{LNG_Expense}",
        'class' => 'icon-edit notext'
      ));
      // แสดงตาราง
      $section->appendChild(createClass('Index\Category\View')->render($request, $login['id'], $typ));
      return $section->render();
    }
    // 404.html
    return \Index\Error\Controller::page404();
  }
}