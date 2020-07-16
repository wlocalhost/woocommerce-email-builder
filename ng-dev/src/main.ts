import { enableProdMode, NgModuleRef } from '@angular/core';
import { APP_BASE_HREF } from '@angular/common';
import { LazyModules, ngxLazyLoadModules } from "@wanoo21/ngx-lazy-modules";

// import { AppModule } from './app/app.module';
import { environment } from './environments/environment';
import { NgModuleDef } from '@angular/core/src/r3_symbols';

if (environment.production) {
  enableProdMode();
}

const LazyModules: LazyModules[] = [
  {
    slug: 'ngb-woocommerce',
    loadModule: () => import('./app/woocommerce-templates/woocommerce-templates.module').then(m => m.WoocommerceTemplatesModule)
  },
  {
    slug: 'ngb-widgets',
    loadModule: () => import('./app/widgets/widgets.module').then(m => m.WidgetsModule)
  },
  {
    slug: 'ngb-settings',
    loadModule: () => import('./app/settings/settings.module').then(m => m.SettingsModule)
  },
  {
    slug: '**',
    loadModule: () => import('./app/app.module').then(m => m.AppModule)
  }
];

const ngx = ngxLazyLoadModules(LazyModules, {
  staticProvider: [{
    provide: APP_BASE_HREF,
    useValue: globalThis.NGB.app_base_href
  }]
});

(async function () {
  const { current_slug } = globalThis.NGB
  const mod = await ngx.load(current_slug)
  console.log(mod)



  setTimeout(() => {
    mod.destroy()
    ngx.load('ngb-woocommercell')
  }, 2000)
  // console.log(moduleRef)
}()).catch(err => console.error(err));

