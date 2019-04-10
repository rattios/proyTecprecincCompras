import { Component, ElementRef } from '@angular/core';
import { CommonModule, NgClass} from '@angular/common';
import { FormsModule} from '@angular/forms';
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
  public noLeidos:any=0;
  public nMensajes:any=0;
  public mensaje:any;
  public mensajes:any=[];
  public departamentos:any=JSON.parse(localStorage.getItem('tecprecinc_departamentos'));  ;
  public depart:any=localStorage.getItem('tecprecinc_departamento_id');;

  constructor(private router: Router,private el: ElementRef,private http: HttpClient, private ruta: RutaService) {


    this.http.get(this.ruta.get_ruta()+'usuarios2/'+localStorage.getItem('tecprecinc_usuario_id'))
       .toPromise()
       .then(
       data => {
          var resp:any=data;
          console.log(data);
          console.log(resp[0].departamento2);
          var depart=resp[0].departamento2;
            
          localStorage.setItem('tecprecinc_departamentos', JSON.stringify(depart));
          this.departamentos=JSON.parse(localStorage.getItem('tecprecinc_departamentos'));
          //this.loading=false;
        },
       msg => { 
         console.log(msg);
         //this.loading=false;
       });

  }

  //wait for the component to render completely
  ngOnInit(): void {
    setTimeout(()=>{    //<<<---    using ()=> syntax
      this.depart=localStorage.getItem('tecprecinc_departamento_id');
      this.departamentos=JSON.parse(localStorage.getItem('tecprecinc_departamentos'));            
    },100);
    
    console.log(JSON.parse(localStorage.getItem('tecprecinc_departamentos')));
    var nativeElement: HTMLElement = this.el.nativeElement,
    parentElement: HTMLElement = nativeElement.parentElement;
    // move all children out of the element
    while (nativeElement.firstChild) {
      parentElement.insertBefore(nativeElement.firstChild, nativeElement);
    }
    // remove the empty element(the host)
    parentElement.removeChild(nativeElement);
    this.nombre= localStorage.getItem('tecprecinc_nombre');

    /*this.http.get(this.ruta.get_ruta()+'mensajes/departamento/'+localStorage.getItem('tecprecinc_departamento_id'))
         .toPromise()
         .then(
         data => {
           console.log(data);
           this.mensaje=data;
           this.mensaje=this.mensaje.mensajes;
           for (var i = 0; i < this.mensaje.length; i++) {
             if(i<=10) {
               this.mensajes.push(this.mensaje[i]);
               this.nMensajes++;
             }
             
           }
           for (var i = 0; i < this.mensaje.length; i++) {
             if(this.mensaje[i].estado==1) {
               this.noLeidos++;
             }
           }
          },
         msg => { 
           console.log(msg);
           //alert(msg.error);
         });*/

  }

  addDp(d){
    console.log(d.target.value);
    localStorage.setItem('tecprecinc_departamento_id', d.target.value);
    location.reload();
    
  }

  reload(){
    
    this.http.get(this.ruta.get_ruta()+'mensajes/departamento/'+localStorage.getItem('tecprecinc_departamento_id'))
         .toPromise()
         .then(
         data => {
           this.mensaje=[];
            this.mensajes=[];
            this.nMensajes=0;
            this.noLeidos=0;
           console.log(data);
           this.mensaje=data;
           this.mensaje=this.mensaje.mensajes;
           for (var i = 0; i < this.mensaje.length; i++) {
             if(i<=7) {
               this.mensajes.push(this.mensaje[i]);
               this.nMensajes++;
             }
             
           }
           for (var i = 0; i < this.mensaje.length; i++) {
             if(this.mensaje[i].estado==1) {
               this.noLeidos++;
             }
           }
          },
         msg => { 
           console.log(msg);
           //alert(msg.error);
         });
  }

  ir(){
    this.router.navigateByUrl('/mensajes');
  }

  logout(){
    localStorage.setItem('tecprecinc_token', '');
    localStorage.setItem('tecprecinc_nombre', '');
    localStorage.setItem('tecprecinc_departamento_id', '');
    localStorage.setItem('tecprecinc_nombre', '');
    localStorage.setItem('tecprecinc_rol', '');
    localStorage.setItem('tecprecinc_departamentos', JSON.stringify([]));
  }
}
