import { Component, ElementRef } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-header',
  templateUrl: './app-header.component.html'
})
export class AppHeader {
  public nombre;
  constructor(private router: Router,private el: ElementRef) { }

  //wait for the component to render completely
  ngOnInit(): void {
    var nativeElement: HTMLElement = this.el.nativeElement,
    parentElement: HTMLElement = nativeElement.parentElement;
    // move all children out of the element
    while (nativeElement.firstChild) {
      parentElement.insertBefore(nativeElement.firstChild, nativeElement);
    }
    // remove the empty element(the host)
    parentElement.removeChild(nativeElement);
    this.nombre= localStorage.getItem('tecprecinc_nombre');
  }

  logout(){
    localStorage.setItem('tecprecinc_token', '');
    localStorage.setItem('tecprecinc_nombre', '');
    localStorage.setItem('tecprecinc_departamento_id', '');
    localStorage.setItem('tecprecinc_nombre', '');
    localStorage.setItem('tecprecinc_rol', '');
  }
}
