<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace Somnambulist\EnvironmentLoader;

use Illuminate\Config\Repository;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Class ServiceProvider
 *
 * @package    Somnambulist\EnvironmentLoader
 * @subpackage Somnambulist\EnvironmentLoader\ServiceProvider
 * @author     Dave Redfern
 */
class ServiceProvider extends BaseServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom($this->getConfigPath(), 'environment_loader');

        $this->publishes([$this->getConfigPath() => config_path('environment_loader.php'),], 'config');

        $this->registerBootProviders($this->app['config']);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerServiceProviders($this->app['config']);
        $this->registerFacadeAliases($this->app['config']);
    }

    /**
     * Registers the boot providers to run
     *
     * @param Repository $config
     */
    protected function registerBootProviders(Repository $config)
    {
        foreach ($this->getComponentsFromConfig($config, 'boot', getenv('APP_ENV')) as $provider) {
            $this->app->register($provider);
        }
    }

    /**
     * Register the service providers under the "register" call
     *
     * @param Repository $config
     */
    protected function registerServiceProviders(Repository $config)
    {
        foreach ($this->getComponentsFromConfig($config, 'register', getenv('APP_ENV')) as $provider) {
            $this->app->register($provider);
        }
    }

    /**
     * Registers any facades that have been defined
     *
     * @param Repository $config
     */
    protected function registerFacadeAliases(Repository $config)
    {
        $loader = AliasLoader::getInstance();

        foreach ($this->getComponentsFromConfig($config, 'facades', getenv('APP_ENV')) as $alias => $facade) {
            $loader->alias($alias, $facade);
        }
    }

    /**
     * Fetches a specific config section, returning empty array by default
     *
     * @param Repository $config
     * @param string     $section
     * @param string     $env
     *
     * @return array
     */
    protected function getComponentsFromConfig(Repository $config, $section, $env)
    {
        return $config->get('environment_loader.' . $section . '.' . $env, []);
    }

    /**
     * @return string
     */
    protected function getConfigPath()
    {
        return __DIR__ . '/../config/environment_loader.php';
    }
}
