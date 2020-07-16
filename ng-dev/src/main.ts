import { enableProdMode } from '@angular/core';
import { APP_BASE_HREF } from '@angular/common';
import { ngxLazyLoadModules, NgxLazyModule } from "@wanoo21/ngx-lazy-modules";

// import { AppModule } from './app/app.module';
import { environment } from './environments/environment';

if (environment.production) {
  enableProdMode();
}

const LazyModules: NgxLazyModule[] = [
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

// const event = new CustomEvent('ngxLoaded', { detail: { ngx } });

(async function () {
  const { current_slug } = globalThis.NGB
  const mod = await ngx.load(current_slug)

  mod.onDestroy(function () {
    console.log('Module destroyed')
  })

  setTimeout(() => {
    ngx.load('ngb-woocommerce')
  }, 2000)
}()).catch(err => console.error(err));

