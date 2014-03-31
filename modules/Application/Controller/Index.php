<?php
namespace Application\Controller;

use Application\Controller\Shared as SharedController;
use Packagist\Api\Client as PackagistClient;

class Index extends SharedController
{
    public function indexAction()
    {

        $moduleHelper = $this->getService('module.helper');
        $selectedModule = $moduleHelper->getByID(1);

        return $this->render('Application:index:index.html.php', compact('selectedModule'));
    }

    public function createModuleAction()
    {
        return $this->render('Application:index:createmodule.html.php');
    }

    public function lookupPackagistAction()
    {
        $client = new PackagistClient();
        $package = $client->get($this->queryString('package'));
//        $maintainers = $package->getMaintainers();
        $desc = $package->getDescription();

        return json_encode(array(
            'desc'   => $desc,
            'status' => 'success'
        ));

    }

    /**
     * @todo - make thumbnail of original screenshot
     * @todo - move it to public DIR
     * @todo - insert record into DB
     */
    public function uploadScreenshotAction()
    {
        $file = $_FILES['file'];


//        $moduleID = $this->session('wizardModule')->getID();
        $moduleID = '1';

        // Check Ext
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        if(!in_array($ext, array('png', 'jpg', 'jpeg', 'gif'))) {
            return 'E_INVALID_EXTENSION';
        }

        $config = $this->getConfig();
        $newFilename = sprintf('%s_screenshot_%s.%s', $moduleID, microtime(true), $ext);
        $newPath = $config['module']['screenshots_public_dir'] . '/' . $newFilename;

        $ret = move_uploaded_file($file['tmp_name'], $newPath);

    }
}
