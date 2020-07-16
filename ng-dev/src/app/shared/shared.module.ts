import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { BrowserModule } from '@angular/platform-browser';
import { NoopAnimationsModule } from '@angular/platform-browser/animations';
import { IpEmailBuilderModule, IP_CONFIG } from 'ip-email-builder'

@NgModule({
  exports: [
    CommonModule,
    BrowserModule,
    NoopAnimationsModule,
    IpEmailBuilderModule
  ],
  providers: [
    { provide: IP_CONFIG, useValue: { xApiKey: 'ULMnDh2ens78ge40yU29Q7bbF6r0N5B96VNbebCJ' } }
  ]
})
export class SharedModule { }
