<?php 

add_action( 'woocommerce_product_query', 'pre_get_posts_hide_invisible_products', PHP_INT_MAX );
add_action( 'pre_get_posts', 'pre_get_posts_hide_invisible_products', PHP_INT_MAX );



            function pre_get_posts_hide_invisible_products( $query ) {
		if ( false === filter_var( apply_filters( 'alg_wc_pvbur_can_search', true, $query ), FILTER_VALIDATE_BOOLEAN ) ) {
			return;
		}

		remove_action( 'woocommerce_product_query', 'pre_get_posts_hide_invisible_products', PHP_INT_MAX );
		remove_action( 'pre_get_posts',  'pre_get_posts_hide_invisible_products' , PHP_INT_MAX );

		$post__not_in          = $query->get( 'post__not_in' );
		$post__not_in          = empty( $post__not_in ) ? array() : $post__not_in;
		$current_user_roles    = alg_wc_pvbur_get_current_user_all_roles();
                $current_user_ids = get_current_user_id();
                
                if (in_array("administrator", $current_user_roles) || in_array("contentmanager", $current_user_roles)){
                
                  $invisible_product_ids = array();
                
                }else{
                    
                  $invisible_product_ids = alg_wc_pvbur_get_invisible_products_ids( $current_user_roles,$current_user_ids,false );  
                  $visible_product_ids_userlist = alg_wc_pvbur_get_invisible_products_ids_userlist( $current_user_roles,$current_user_ids,false );  
                  $visible_product_ids_userlist_views = alg_wc_pvbur_get_invisible_products_ids_userlist_views( $current_user_roles,$current_user_ids,false );  
                  $results = array_merge($invisible_product_ids,$visible_product_ids_userlist_views);
                  
                  // echo '<pre>';
                 //  print_r($invisible_product_ids);
                 //  print_r($visible_product_ids_userlist_views);
                  // print_r($results);exit;
                  
                  $invisible_product_ids = array_diff($visible_product_ids_userlist,$results);
                  
                 
                }
                
                
               // print_r($invisible_product_ids);exit;
                
                if ( is_array( $invisible_product_ids ) && count( $invisible_product_ids ) > 0 ) {
			foreach ( $invisible_product_ids as $invisible_product_id ) {
				$filter = apply_filters( 'alg_wc_pvbur_is_visible', false, $current_user_roles, $invisible_product_id );
				if ( ! filter_var( $filter, FILTER_VALIDATE_BOOLEAN ) ) {
					$post__not_in[] = $invisible_product_id;
				}
			}
		}

		$post__not_in = array_unique( $post__not_in );
		$query->set( 'post__not_in', apply_filters( 'alg_wc_pvbur_post__not_in', $post__not_in, $invisible_product_ids  ) );
		do_action( 'alg_wc_pvbur_hide_products_query', $query, $invisible_product_ids );

		add_action( 'pre_get_posts',  'pre_get_posts_hide_invisible_products', PHP_INT_MAX );
		add_action( 'woocommerce_product_query','pre_get_posts_hide_invisible_products' , PHP_INT_MAX );
            }   
        
        if ( ! function_exists( 'alg_wc_pvbur_get_current_user_all_roles' ) ) {
	/**
	 * get_current_user_all_roles.
	 *
	 * @version 1.1.4
	 * @since   1.0.0
	 */
            function alg_wc_pvbur_get_current_user_all_roles() {
                    if ( ! function_exists( 'wp_get_current_user' ) ) {
                            require_once( ABSPATH . 'wp-includes/pluggable.php' );
                    }
                    $current_user = wp_get_current_user();
                    return ( ! empty( $current_user->roles ) ) ? $current_user->roles : array( 'guest' );
            }
        }

if ( ! function_exists( 'alg_wc_pvbur_get_invisible_products_ids' ) ) {
	/**
	 * Get invisible products ids
	 *
	 * @version 1.2.1
	 * @since   1.2.1
	 */
	function alg_wc_pvbur_get_invisible_products_ids( $roles = array(),$current_user_ids = "", $cache = true ) {
		if ( $cache ) {
			$invisible_products_ids_query_name = "awcpvbur_inv_pids_" . md5( implode( "_", $roles ) );
			if ( false === ( $invisible_product_ids = get_transient( $invisible_products_ids_query_name ) ) ) {
				$invisible_products    = alg_wc_pvbur_get_invisible_products( $roles,$current_user_ids );
				$invisible_product_ids = $invisible_products->posts;
				set_transient( $invisible_products_ids_query_name, $invisible_product_ids );
			}
		} else {
			$invisible_products    = alg_wc_pvbur_get_invisible_products( $roles ,$current_user_ids );
			$invisible_product_ids = $invisible_products->posts;
		}

		return $invisible_product_ids;
	}
        
        function alg_wc_pvbur_get_invisible_products_ids_userlist( $roles = array(),$current_user_ids = "", $cache = true ) {
		if ( $cache ) {
			$invisible_products_ids_query_name = "awcpvbur_inv_pids_" . md5( implode( "_", $roles ) );
			if ( false === ( $invisible_product_ids = get_transient( $invisible_products_ids_query_name ) ) ) {
				$invisible_products    = alg_wc_pvbur_get_invisible_products_userlist( $roles,$current_user_ids );
				$invisible_product_ids = $invisible_products->posts;
				set_transient( $invisible_products_ids_query_name, $invisible_product_ids );
			}
		} else {
			$invisible_products    = alg_wc_pvbur_get_invisible_products_userlist( $roles ,$current_user_ids );
			$invisible_product_ids = $invisible_products->posts;
		}

		return $invisible_product_ids;
	}
        function alg_wc_pvbur_get_invisible_products_ids_userlist_views( $roles = array(),$current_user_ids = "", $cache = true ) {
		if ( $cache ) {
			$invisible_products_ids_query_name = "awcpvbur_inv_pids_" . md5( implode( "_", $roles ) );
			if ( false === ( $invisible_product_ids = get_transient( $invisible_products_ids_query_name ) ) ) {
				$invisible_products    = alg_wc_pvbur_get_invisible_products_userlist_views( $roles,$current_user_ids );
				$invisible_product_ids = $invisible_products->posts;
				set_transient( $invisible_products_ids_query_name, $invisible_product_ids );
			}
		} else {
			$invisible_products    = alg_wc_pvbur_get_invisible_products_userlist_views( $roles ,$current_user_ids );
			$invisible_product_ids = $invisible_products->posts;
		}

		return $invisible_product_ids;
	}
        
}

if ( ! function_exists( 'alg_wc_pvbur_is_visible' ) ) {
	/**
	 * is_visible.
	 *
	 * @version 1.2.0
	 * @since   1.1.0
	 */
	function alg_wc_pvbur_is_visible( $current_user_roles, $product_id ) {
		// Per product
		$roles = get_post_meta( $product_id, '_' . 'alg_wc_pvbur_visible', true );
		if ( is_array( $roles ) && ! empty( $roles ) ) {
			$_intersect = array_intersect( $roles, $current_user_roles );
			if ( empty( $_intersect ) ) {
				return alg_wc_pvbur_trigger_is_visible_filter( false, $current_user_roles, $product_id );
			}
		}
		$roles = get_post_meta( $product_id, '_' . 'alg_wc_pvbur_invisible', true );
		if ( is_array( $roles ) && ! empty( $roles ) ) {
			$_intersect = array_intersect( $roles, $current_user_roles );
			if ( ! empty( $_intersect ) ) {
				return alg_wc_pvbur_trigger_is_visible_filter( false, $current_user_roles, $product_id );
			}
		}
		// Bulk
		if ( 'yes' === apply_filters( 'alg_wc_pvbur', 'no', 'bulk_settings' ) ) {
			foreach ( $current_user_roles as $user_role_id ) {
				$visible_products = get_option( 'alg_wc_pvbur_bulk_visible_products_' . $user_role_id, '' );
				if ( ! empty( $visible_products ) ) {
					if ( ! in_array( $product_id, $visible_products ) ) {
						return alg_wc_pvbur_trigger_is_visible_filter( false, $current_user_roles, $product_id );
					}
				}
				$invisible_products = get_option( 'alg_wc_pvbur_bulk_invisible_products_' . $user_role_id, '' );
				if ( ! empty( $invisible_products ) ) {
					if ( in_array( $product_id, $invisible_products ) ) {
						return alg_wc_pvbur_trigger_is_visible_filter( false, $current_user_roles, $product_id );
					}
				}
				$taxonomies = array( 'product_cat', 'product_tag' );
				foreach ( $taxonomies as $taxonomy ) {
					// Getting product terms
					$product_terms_ids = array();
					$_terms            = wp_get_post_terms( $product_id, $taxonomy, array( 'fields' => 'ids' ) );
					if ( ! empty( $_terms ) ) {
						foreach ( $_terms as $_term ) {
							$product_terms_ids[] = $_term;
						}
					}
					// Checking
					$visible_terms = get_option( 'alg_wc_pvbur_bulk_visible_' . $taxonomy . 's_' . $user_role_id, '' );
					if ( ! empty( $visible_terms ) ) {
						$_intersect = array_intersect( $visible_terms, $product_terms_ids );
						if ( empty( $_intersect ) ) {
							return alg_wc_pvbur_trigger_is_visible_filter( false, $current_user_roles, $product_id );
						}
					}
					$invisible_terms = get_option( 'alg_wc_pvbur_bulk_invisible_' . $taxonomy . 's_' . $user_role_id, '' );
					if ( ! empty( $invisible_terms ) ) {
						$_intersect = array_intersect( $invisible_terms, $product_terms_ids );
						if ( ! empty( $_intersect ) ) {
							return alg_wc_pvbur_trigger_is_visible_filter( false, $current_user_roles, $product_id );
						}
					}
				}
			}
		}

		return alg_wc_pvbur_trigger_is_visible_filter( true, $current_user_roles, $product_id );
	}
}

if ( ! function_exists( 'alg_wc_pvbur_get_invisible_products' ) ) {
	/**
	 * Get invisible products
	 *
	 * @version 1.1.9
	 * @since   1.1.9
	 */
	function alg_wc_pvbur_get_invisible_products( $roles = array(),$current_user_ids="" ) {
		$query = new WP_Query( alg_wc_pvbur_get_invisible_products_query_args( $roles,$current_user_ids ) );
               
                
                

		return $query;
	}
       function alg_wc_pvbur_get_invisible_products_userlist( $roles = array(),$current_user_ids="" ) {
		$query = new WP_Query( alg_wc_pvbur_get_invisible_products_query_args_onusersids( $roles,$current_user_ids ) );
               
                
                

		return $query;
	}
         function alg_wc_pvbur_get_invisible_products_userlist_views( $roles = array(),$current_user_ids="" ) {
		$query = new WP_Query( alg_wc_pvbur_get_invisible_products_query_args_onusersids_views( $roles,$current_user_ids ) );
               
                
                

		return $query;
	}
        
        
}
if ( ! function_exists( 'alg_wc_pvbur_get_invisible_products_query_args' ) ) {
	/**
	 * alg_wc_pvbur_get_invisible_products_query_args
	 *
	 * @version 1.2.3
	 * @since   1.1.9
	 */
	function alg_wc_pvbur_get_invisible_products_query_args( $roles = array(),$current_user_ids="" ) {
            
		$query_args = array(
			'fields'           => 'ids',
			'post_type'        => 'product',
			'posts_per_page'   => '-1',
			'suppress_filters' => true,
			'meta_query'       => array()
		);
                
                 
                    
                    $visible_meta_query   = array();
                    $invisible_meta_query = array();
                    $visible_meta_query['relation'] = "OR";
                   // $invisible_meta_query['relation'] = "OR";
                       
                        foreach ( $roles as $role ) {
                            
                                $visible_meta_query[] = array(
                                        'key'     => '_alg_wc_pvbur_visible',
                                        'value'   => '"' . $role . '"',
                                        'compare' => 'LIKE',
                                );
                        }
                        
                        $visible_meta_query[] = array(
                                        'key'     => '_alg_wc_pvbur_uservisible',
                                        'value'   => '"' . $current_user_ids . '"',
                                        'compare' => 'LIKE',
                                );
                        
                       
                        
                      //  $query_args['meta_query']['relation']="OR";
                        $query_args['meta_query'][] = $visible_meta_query;
                      //  $query_args['meta_query'][] = $invisible_meta_query;
                       
                    // echo '<pre>';
                    // print_r($query_args);
                      
                     return $query_args;
                     
                    }
        
        function alg_wc_pvbur_get_invisible_products_query_args_onusersids( $roles = array(),$current_user_ids="" ) {
            
		$query_args = array(
			'fields'           => 'ids',
			'post_type'        => 'product',
			'posts_per_page'   => '-1',
                        'product_cat' => 'add-ons','package',
			'suppress_filters' => true,
			'meta_query'       => array()
		);
                
                return $query_args;
                    
            }
            
        function alg_wc_pvbur_get_invisible_products_query_args_onusersids_views( $roles = array(),$current_user_ids="" ) {
            
		$query_args = array(
			'fields'           => 'ids',
			'post_type'        => 'product',
			'posts_per_page'   => '-1',
			'suppress_filters' => true,
			'meta_query'       => array()
		);
                
                 
                    $invisible_meta_query = array();
                    $visible_meta_query   = array();
                    $visible_meta_query['relation'] = 'OR';
                    $invisible_meta_query['relation'] = 'OR';
                    $invisible_meta_query[] = array(
                                        'key'     => '_alg_wc_pvbur_uservisible',
                                        'value'   => 'i:0;',
                                        'compare' => 'NOT LIKE',
                    );
                    
                    $invisible_meta_query[] = array(
                                        'key'     => '_alg_wc_pvbur_uservisible',
                                        'compare' => 'NOT EXISTS',
                    );
                    
                    $visible_meta_query[] = array(
                                        'key'     => '_alg_wc_pvbur_visible',
                                        'value'   => 'i:0;',
                                        'compare' => 'NOT LIKE',
                    );
                    
                    
                    $visible_meta_query[] = array(
                                        'key'     => '_alg_wc_pvbur_visible',
                                        'compare' => 'NOT EXISTS',
                    );
                    
                    $query_args['meta_query']['relation'] = 'AND';
                    $query_args['meta_query'][] = $visible_meta_query;
                    $query_args['meta_query'][] = $invisible_meta_query;
                    
                   // echo '<pre>';
                   // print_r($query_args);
                    
                    return $query_args;
                    
        }
        
}?>