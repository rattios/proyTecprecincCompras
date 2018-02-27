import { Component, ElementRef } from '@angular/core';
import { CommonModule, NgClass} from '@angular/common';
import { Router } from '@angular/router';
import { HttpClient, HttpParams  } from '@angular/common/http';
import 'rxjs/add/operator/toPromise';
import { RutaService } from '../../services/ruta.service';

@Component({
  selector: 'app-header',
  templateUrl: './app-header.component.html'
})
export class AppHeader {
  public nombre;
  public noLeidos:any;
  public nMensajes:any;
  public mensajes:any;

  constructor(private router: Router,private el: ElementRef,private http: HttpClient, private ruta: RutaService) { }

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

    this.http.get(this.ruta.get_ruta()+'mensajes/departamento/'+localStorage.getItem('tecprecinc_departamento_id'))
         .toPromise()
         .then(
         data => {
           console.log(data);
           this.mensajes=data;
           this.mensajes=this.mensajes.mensajes;
          },
         msg => { 
           console.log(msg);
           alert(msg.error);
         });

  }

  logout(){
    localStorage.setItem('tecprecinc_token', '');
    localStorage.setItem('tecprecinc_nombre', '');
    localStorage.setItem('tecprecinc_departamento_id', '');
    localStorage.setItem('tecprecinc_nombre', '');
    localStorage.setItem('tecprecinc_rol', '');
  }
}
