<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package		TomatoCart
 * @author		TomatoCart Dev Team
 * @copyright	Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://tomatocart.com
 * @since		Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Registry Class
 *
 * @package		TomatoCart
 * @subpackage	tomatocart
 * @category	template-module-controller
 * @author		TomatoCart Dev Team
 * @link		http://tomatocart.com/wiki/
 */
class TOC_Reviews 
{
    var $is_enabled = false,
    $is_moderated = false;

    // class constructor
    function osC_Reviews() {

        $this->enableReviews();
        $this->enableModeration();
    }

    function enableReviews() {
        global $osC_Database, $osC_Customer;

        switch (SERVICE_REVIEW_ENABLE_REVIEWS) {
            case 0:
                $this->is_enabled = true;
                break;
            case 1:
                if ($osC_Customer->isLoggedOn()) {
                    $this->is_enabled = true;
                } else {
                    $this->is_enabled = false;
                }
                break;
            case 2:
                if ($this->hasPurchased() == true) {
                    $this->is_enabled = true;
                } else {
                    $this->is_enabled = false;
                }
                break;
            default:
                $this->is_enabled = false;
                break;
        }
    }

    function hasPurchased() {
        global $osC_Database, $osC_Customer;

        $Qhaspurchased = $osC_Database->query('select count(*) as total from :table_orders o, :table_orders_products op, :table_products p where o.customers_id = :customers_id and o.orders_id = op.orders_id and op.products_id = p.products_id and op.products_id = :products_id');
        $Qhaspurchased->bindRaw(':table_orders', TABLE_ORDERS);
        $Qhaspurchased->bindRaw(':table_orders_products', TABLE_ORDERS_PRODUCTS);
        $Qhaspurchased->bindRaw(':table_products', TABLE_PRODUCTS);
        $Qhaspurchased->bindInt(':customers_id', $osC_Customer->getID());
        $Qhaspurchased->bindInt(':products_id', $_GET['products_id']);
        $Qhaspurchased->execute();

        if ($Qhaspurchased->valueInt('total') >= '1') {
            return true;
        } else {
            return false;
        }
    }

    function enableModeration() {
        global $osC_Database, $osC_Customer;

        switch (SERVICE_REVIEW_ENABLE_MODERATION) {
            case -1:
                $this->is_moderated = false;
                break;
            case 0:
                if ($osC_Customer->isLoggedOn()) {
                    $this->is_moderated = false;
                } else {
                    $this->is_moderated = true;
                }
                break;
            case 1:
                $this->is_moderated = true;
                break;
            default:
                $this->is_moderated = true;
                break;
        }
    }

    function getTotal($id) {
        global $osC_Database, $osC_Language;

        $Qcheck = $osC_Database->query('select count(*) as total from :table_reviews where products_id = :products_id and languages_id = :languages_id and reviews_status = 1 limit 1');
        $Qcheck->bindTable(':table_reviews', TABLE_REVIEWS);
        $Qcheck->bindInt(':products_id', $id);
        $Qcheck->bindInt(':languages_id', $osC_Language->getID());
        $Qcheck->execute();

        return $Qcheck->valueInt('total');
    }

    function exists($id = null, $groupped = false) {
        global $osC_Database, $osC_Language;

        $Qcheck = $osC_Database->query('select reviews_id from :table_reviews where');

        if (is_numeric($id)) {
            if ($groupped === false) {
                $Qcheck->appendQuery('reviews_id = :reviews_id and');
                $Qcheck->bindInt(':reviews_id', $id);
            } else {
                $Qcheck->appendQuery('products_id = :products_id and');
                $Qcheck->bindInt(':products_id', $id);
            }
        }

        $Qcheck->appendQuery('languages_id = :languages_id and reviews_status = 1 limit 1');
        $Qcheck->bindTable(':table_reviews', TABLE_REVIEWS);
        $Qcheck->bindInt(':languages_id', $osC_Language->getID());
        $Qcheck->execute();

        if ($Qcheck->numberOfRows() === 1) {
            return true;
        }

        return false;
    }

    function getProductID($id) {
        global $osC_Database;

        $Qreview = $osC_Database->query('select products_id from :table_reviews where reviews_id = :reviews_id');
        $Qreview->bindTable(':table_reviews', TABLE_REVIEWS);
        $Qreview->bindInt(':reviews_id', $id);
        $Qreview->execute();

        return $Qreview->valueInt('products_id');
    }

    function &getListing($id = null) {
        global $osC_Database, $osC_Language, $osC_Image;

        if (is_numeric($id)) {
            $Qreviews = $osC_Database->query('select reviews_id, reviews_text, reviews_rating, date_added, customers_name from :table_reviews where products_id = :products_id and languages_id = :languages_id and reviews_status = 1 order by reviews_id desc');
            $Qreviews->bindInt(':products_id', $id);
            $Qreviews->bindInt(':languages_id', $osC_Language->getID());
        } else {
            $Qreviews = $osC_Database->query('select r.reviews_id, left(r.reviews_text, 100) as reviews_text, r.reviews_rating, r.date_added, r.customers_name, p.products_id, p.products_price, p.products_tax_class_id, pd.products_name, pd.products_keyword, i.image from :table_reviews r, :table_products p left join :table_products_images i on (p.products_id = i.products_id and i.default_flag = :default_flag), :table_products_description pd where r.reviews_status = 1 and r.languages_id = :languages_id and r.products_id = p.products_id and p.products_status = 1 and p.products_id = pd.products_id and pd.language_id = :language_id order by r.reviews_id desc');
            $Qreviews->bindTable(':table_products', TABLE_PRODUCTS);
            $Qreviews->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
            $Qreviews->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
            $Qreviews->bindInt(':default_flag', 1);
            $Qreviews->bindInt(':languages_id', $osC_Language->getID());
            $Qreviews->bindInt(':language_id', $osC_Language->getID());
        }
        $Qreviews->bindTable(':table_reviews', TABLE_REVIEWS);
        $Qreviews->setBatchLimit((isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1), MAX_DISPLAY_NEW_REVIEWS);
        $Qreviews->execute();

        return $Qreviews;
    }

    function &getEntry($id) {
        global $osC_Database, $osC_Language;

        $Qreviews = $osC_Database->query('select reviews_id, reviews_text, reviews_rating, date_added, customers_name from :table_reviews where reviews_id = :reviews_id and languages_id = :languages_id and reviews_status = 1');
        $Qreviews->bindTable(':table_reviews', TABLE_REVIEWS);
        $Qreviews->bindInt(':reviews_id', $id);
        $Qreviews->bindInt(':languages_id', $osC_Language->getID());
        $Qreviews->execute();

        return $Qreviews;
    }

    function saveEntry($data) {
        global $osC_Database, $osC_Language;

        if ( is_array($data['rating']) ) {
            $total = 0;
            foreach($data['rating'] as $value) {
                $total += $value;
            }

            $reviews_rating =  $total/ count($data['rating']);
        } else {
            $reviews_rating = $data['rating'];
        }

        $Qreview = $osC_Database->query('insert into :table_reviews (products_id, customers_id, customers_name, reviews_rating, languages_id, reviews_text, reviews_status, date_added) values (:products_id, :customers_id, :customers_name, :reviews_rating, :languages_id, :reviews_text, :reviews_status, now())');
        $Qreview->bindTable(':table_reviews', TABLE_REVIEWS);
        $Qreview->bindInt(':products_id', $data['products_id']);
        $Qreview->bindInt(':customers_id', $data['customer_id']);
        $Qreview->bindValue(':customers_name', $data['customer_name']);
        $Qreview->bindValue(':reviews_rating', $reviews_rating);
        $Qreview->bindInt(':languages_id', $osC_Language->getID());
        $Qreview->bindValue(':reviews_text', $data['review']);
        $Qreview->bindInt(':reviews_status', $data['status']);
        $Qreview->execute();

        if ( is_array($data['rating']) ) {
            $reviews_id = $osC_Database->nextID();

            foreach($data['rating'] as $ratings_id => $value){
                $Qratings = $osC_Database->query('insert into :table_customers_ratings (ratings_id, customers_id, reviews_id, ratings_value ) values ( :ratings_id, :customers_id, :reviews_id, :ratings_value )');
                $Qratings->bindTable(':table_customers_ratings', TABLE_CUSTOMERS_RATINGS);
                $Qratings->bindValue(':ratings_value', $value);
                $Qratings->bindInt(':reviews_id', $reviews_id);
                $Qratings->bindInt(':customers_id', $data['customer_id']);
                $Qratings->bindInt(':ratings_id', $ratings_id);
                $Qratings->execute();
            }
        }
    }

    function getReviewsCount($id) {
        global $osC_Database, $osC_Language;

        $Qreview = $osC_Database->query('select count(*) as reviews_count from :table_reviews where products_id = :products_id and languages_id = :languages_id');
        $Qreview->bindTable(':table_reviews', TABLE_REVIEWS);
        $Qreview->bindInt(':languages_id', $osC_Language->getID());
        $Qreview->bindInt(':products_id', $id);
        $Qreview->execute();

        return $Qreview->ValueInt('reviews_count');
    }

    function getCustomersRatings($reviews_id) {
        global $osC_Database, $osC_Language;

        $Qratings = $osC_Database->query('select r.ratings_id, rd.ratings_text, r.ratings_value from :table_customers_ratings r inner join :table_ratings_description rd on r.ratings_id = rd.ratings_id where rd.languages_id = :languages_id and r.reviews_id = :reviews_id');
        $Qratings->bindTable(':table_customers_ratings', TABLE_CUSTOMERS_RATINGS);
        $Qratings->bindTable(':table_ratings_description', TABLE_RATINGS_DESCRIPTION);
        $Qratings->bindInt(':languages_id', $osC_Language->getID());
        $Qratings->bindInt(':reviews_id', $reviews_id);
        $Qratings->execute();

        $ratings = array();
        while($Qratings->next()){
            $ratings[] = array('value' => $Qratings->valueInt('ratings_value'), 'name' => $Qratings->value('ratings_text'));
        }
        return $ratings;
    }

    function getCategoryRatings($categories_id) {
        global $osC_Database, $osC_Language;

        $Qcategory = $osC_Database->query('select cr.ratings_id, rd.ratings_text from :toc_categories_ratings cr inner join :table_ratings_description rd on cr.ratings_id = rd.ratings_id where cr.categories_id = :categories_id and rd.languages_id = :languages_id');
        $Qcategory->bindTable(':toc_categories_ratings', TABLE_CATEGORIES_RATINGS);
        $Qcategory->bindTable(':table_ratings_description', TABLE_RATINGS_DESCRIPTION);
        $Qcategory->bindInt(':categories_id', $categories_id);
        $Qcategory->bindInt(':languages_id', $osC_Language->getID());
        $Qcategory->execute();

        $ratings = array();
        while ( $Qcategory->next() ) {
            $ratings[$Qcategory->valueInt('ratings_id')] = $Qcategory->value('ratings_text');
        }
        $Qcategory->freeResult();

        return $ratings;
    }

}
?>
