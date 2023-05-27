<?php
namespace Template;
final class Twig {
	private $data = array();

            public function __construct($registry) {
                $this->registry = $registry;
            }
            

	public function set($key, $value) {
		$this->data[$key] = $value;
	}
	
	public function render($filename, $code = '') {
		if (!$code) {
			$file = DIR_TEMPLATE . $filename . '.twig';

			if (defined('DIR_CATALOG') && is_file(DIR_MODIFICATION . 'admin/view/template/' . $filename . '.twig')) {	
                $code = file_get_contents(DIR_MODIFICATION . 'admin/view/template/' . $filename . '.twig');
            } elseif (is_file(DIR_MODIFICATION . 'catalog/view/theme/' . $filename . '.twig')) {
                $code = file_get_contents(DIR_MODIFICATION . 'catalog/view/theme/' . $filename . '.twig');
            } elseif (is_file($file)) {
				$code = file_get_contents($file);
			} else {
				throw new \Exception('Error: Could not load template ' . $file . '!');
				exit();
			}
		}

		// initialize Twig environment
		$config = array(
			'autoescape'  => false,
			'debug'       => false,
			'auto_reload' => true,
			'cache'       => DIR_CACHE . 'template/'
		);

		try {
			
            //d_twig_manager.xml
            //$loader = new \Twig\Loader\ArrayLoader(array($filename . '.twig' => $code));
			$loader = new \Twig\Loader\FilesystemLoader(str_replace($filename.'.twig','', modification(DIR_TEMPLATE.$filename.'.twig')));
            

			$twig = new \Twig\Environment($loader, $config);

            //d_twig_manager.xml >= 3.0.3.5 
            if (file_exists(DIR_SYSTEM . 'library/template/Twig/Extension/DTwigManager.php')) {
                require_once(DIR_SYSTEM . 'library/template/Twig/Extension/DTwigManager.php');
                $twig->addExtension(new \Twig_Extension_DTwigManager($this->registry));
            }
            

			return $twig->render($filename . '.twig', $this->data);
		} catch (Exception $e) {
			trigger_error('Error: Could not load template ' . $filename . '!');
			exit();
		}	
	}	
}
