import { Component } from '@angular/core';
import {CommonModule} from '@angular/common';
import { HttpClient, HttpParams  } from '@angular/common/http';
import 'rxjs/add/operator/toPromise';
import { RutaService } from '../../../services/ruta.service';

@Component({
  templateUrl: 'devoluciones.component.html'
})
export class devolucionesComponent {
  public prov: any;
  public transferencias: any=[];
  public productos: any;
  public informacion: any;
  public proveedor: any='';
  public loading=true;
  public verInfo=false;
  constructor(private http: HttpClient, private ruta: RutaService) {

  }

   ngOnInit(): void {

      this.http.get(this.ruta.get_ruta()+'transferencias/index/devolucion')
           .toPromise()
           .then(
           data => {
             console.log(data);
             this.prov=data;
              
              this.transferencias=this.prov.devoluciones;

              for (var i = 0; i < this.transferencias.length; i++) {
                if(this.transferencias[i].almacen==1) {
                  this.transferencias[i].nombreAlmacen=this.transferencias[i].departamento.nombre;
                  this.transferencias[i].nombreAlmacenDestino='Almacen Principal';
                }else if(this.transferencias[i].almacen==2){
                  this.transferencias[i].nombreAlmacen=this.transferencias[i].departamento.nombre;
                  this.transferencias[i].nombreAlmacenDestino='Almacen Secundario';
                }
                
                if(this.transferencias[i].tipo==1) {
                  this.transferencias[i].nombreTipo='Transferencia pura';
                }else if(this.transferencias[i].tipo==2){
                  this.transferencias[i].nombreTipo='Devolucion';
                }else if(this.transferencias[i].tipo==3){
                  this.transferencias[i].nombreTipo='Transferencia de bien de uso';
                }

                if(this.transferencias[i].estado==1) {
                  this.transferencias[i].nombreEstado='En curso';
                }else if(this.transferencias[i].estado==2) {
                  this.transferencias[i].nombreEstado='Completado';
                }
              }
              
              this.loading=false;
            },
           msg => { 
             console.log(msg);
             this.loading=false;
             alert('Ha ocurrido un error!');
           });
    }

}
