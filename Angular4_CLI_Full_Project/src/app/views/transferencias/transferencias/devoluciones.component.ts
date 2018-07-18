import { Component, OnInit, Input,Pipe, PipeTransform } from '@angular/core';
import {CommonModule, NgClass, DatePipe } from '@angular/common';
import { HttpClient, HttpParams  } from '@angular/common/http';
import 'rxjs/add/operator/toPromise';
import { saveAs } from 'file-saver/FileSaver';
import { RutaService } from '../../../services/ruta.service';
import {MatDatepicker} from '@angular/material/datepicker';
import {DateAdapter} from '@angular/material';

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
  public inicioD2:any=new Date();
  public finD2:any=new Date();
  constructor(private http: HttpClient, private ruta: RutaService) {

  }

   ngOnInit(): void {
       var principio:any= new Date();
     var mes:any=principio.getMonth()+1; 
     var anio:any=principio.getYear()+1900;
     principio=mes+"-01-"+anio;
     principio=new Date(principio);
     this.inicioD2=principio;
     console.log(principio);
      var datePipe = new DatePipe("en-US");
      console.log(datePipe.transform(new Date(), 'dd-MM-yyyy'));
      this.http.get(this.ruta.get_ruta()+'transferencias/index/devolucion?inicio='+datePipe.transform(principio, 'dd-MM-yyyy')+'&fin='+datePipe.transform(new Date(), 'dd-MM-yyyy'))
           .toPromise()
           .then(
           data => {
             console.log(data);
             this.prov=data;
              
              this.transferencias=this.prov.devoluciones;
              if(this.transferencias.length>0) {
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
              }else{
                this.transferencias=[{
                  tipo:2,
                  cantidad_transf:'',
                  stock_central:{nombre:''},
                  almacen_origen:{nombre:''},
                  almacen_destino:{nombre:''},
                  nombreTipo:'',
                  nombreEstado:'',
                  created_at:''
                }];
              }
             
              
              this.loading=false;
            },
           msg => { 
             console.log(msg);
             this.loading=false;
             alert('Ha ocurrido un error!');
           });
    }

    buscar(){
      this.loading=true;
      console.log(this.inicioD2);
      console.log(this.finD2);
      var datePipe = new DatePipe("en-US");
      this.http.get(this.ruta.get_ruta()+'transferencias/index/devolucion?inicio='+datePipe.transform(this.inicioD2, 'dd-MM-yyyy')+'&fin='+datePipe.transform(this.finD2, 'dd-MM-yyyy'))
           .toPromise()
           .then(
           data => {
              console.log(data);
             this.prov=data;
              
              this.transferencias=this.prov.devoluciones;
              if(this.transferencias.length>0) {
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
              }else{
                this.transferencias=[{
                  tipo:2,
                  cantidad_transf:'',
                  stock_central:{nombre:''},
                  almacen_origen:{nombre:''},
                  almacen_destino:{nombre:''},
                  nombreTipo:'',
                  nombreEstado:'',
                  created_at:''
                }];
              }
              this.loading=false;
            },
           msg => { 
             console.log(msg);
             this.loading=false;
           });
    }

}
