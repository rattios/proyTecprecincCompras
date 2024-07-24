import { Component, OnInit, Input, ViewChild} from '@angular/core';
import {CommonModule, NgClass} from '@angular/common';
import { HttpClient, HttpParams  } from '@angular/common/http';
import 'rxjs/add/operator/toPromise';
import { ModalDirective } from 'ngx-bootstrap/modal/modal.component';
import { RutaService } from '../../services/ruta.service';
import { SharedService } from './shared.service';

@Component({
  selector: 'app-info',
  templateUrl: 'info.component.html'
})
export class infoComponent {
  public prov: any;
  public pedidos: any;
  public productos: any;
  public proveedor: any='';
  public showTransferencia=false;
  public aTransferir:any;
  public autorizante= localStorage.getItem('tecprecinc_nombre')+' '+localStorage.getItem('tecprecinc_apellido');
  public fechaaprobacion=new Date();
  public largeModal2:any;

  @Input() informacion:any;
  @Input() remito:any;

  constructor(private http: HttpClient, private ruta: RutaService,private sharedService: SharedService) {

  }
  

   public cc:any;

   ngOnInit(): void {
      console.log(this.informacion);
      //this.remito.solicitud=[];
      for (var i = 0; i < this.informacion.solicitud.length; i++) {
        this.informacion.solicitud[i].usuario=this.informacion.usuario;
        this.informacion.solicitud[i].totales=this.informacion.solicitud[i].pivot.cantidad*this.informacion.solicitud[i].precio;
        
      }
      if(this.informacion!=undefined) {
       
      }
      this.http.get(this.ruta.get_ruta()+'centro_costos')
           .toPromise()
           .then(
           data => {
             this.cc=data;
               this.cc=this.cc.CentroCostos;
              console.log(this.cc);
            },
           msg => { 
             console.log(msg);
             this.http.get(this.ruta.get_ruta()+'centro_costos')
               .toPromise()
               .then(
               data => {
                 this.cc=data;
                   this.cc=this.cc.CentroCostos;
                  console.log(this.cc);
                },
               msg => { 
                 console.log(msg);
               });
           });
    }

    quitar(id){
       console.log(id);
       for (var i = 0; i < this.informacion.solicitud.length; i++) {
        if(this.informacion.solicitud[i].id==id) {
          console.log(this.informacion.solicitud[i]);
          this.informacion.solicitud.splice(i, 1);
        }
       }
    }

    ccc(centro_costo_id) {
      console.log(centro_costo_id.target.value);
      console.log(this.informacion);
    }

    public aPicking:any;
    public showPicking:any;
    selectPicking(item){
      item.departamento=this.informacion.usuario.departamento;
      //item.informacion=this.informacion;
      item.usuario_id=this.informacion.usuario.id;
      console.log(item);
      this.sharedService.cartData.emit("onEvent: chato");
      this.aPicking=item;
      this.showPicking=true;
    }

    /*picking(item){
      item.departamento=this.informacion.usuario.departamento;
      console.log(item);
      //alert(JSON.stringify(item));
      var send = {
        picking: JSON.stringify(item)
      }

      this.http.post(this.ruta.get_ruta()+'pedidos/picking',send)
           .toPromise()
           .then(
           data => {
             console.log(data);
             var rec:any;
             rec=data;
             console.log(this.informacion);
             console.log(rec);
             console.log(rec.picking);
             for (var i = 0; i < this.informacion.solicitud.length; i++) {
               if(this.informacion.solicitud[i].id==rec.picking.id) {
                 this.informacion.solicitud[i]=rec.picking;
               }
             }
            },
           msg => { 
             console.log(msg);
             
           });
    }*/
    apresupuesto(item){
      console.log(item);
      localStorage.setItem("apresupuesto", JSON.stringify(item) );
    }
    transferencia(item){
      this.aTransferir=item;
      this.showTransferencia=true;
    }

    cerrar2(){
      console.log(this.largeModal2);
    }
    public success=false;
    public fail=false;
    editar_observacion(id){
      console.log(id);
      console.log(this.informacion.observaciones);
      var enviar={
        observaciones:this.informacion.observaciones,
        centro_costos_id:this.informacion.centro_costos_id,
        solicitud:JSON.stringify(this.informacion.solicitud)
      }
      console.log(enviar);
      this.http.put(this.ruta.get_ruta()+'editar_observacion/'+id,enviar)
           .toPromise()
           .then(
           data => {
             console.log(data);
             alert('¡Se guardaron los cambios con éxito!');
              this.success=true;
              setTimeout(() => {  
                this.success=false;
              }, 4000);

            },
           msg => { 
             console.log(msg);
             alert('¡Falló al guardar los cambios!');
             this.fail=true;
              setTimeout(() => {  
                this.fail=false;
              }, 4000);
             
           });
    }

    editar_cc(id){
      console.log(id);
      console.log(this.informacion.solicitud);
      var enviar={
        solicitud:JSON.stringify(this.informacion.solicitud)
      }
      this.http.put(this.ruta.get_ruta()+'editar_cc/'+id,enviar)
           .toPromise()
           .then(
           data => {
             console.log(data);
             alert('¡Se guardaron los cambios con éxito!');
              this.success=true;
              setTimeout(() => {  
                this.success=false;
              }, 4000);

            },
           msg => { 
             console.log(msg);
             alert('¡Falló al guardar los cambios!');
             this.fail=true;
              setTimeout(() => {  
                this.fail=false;
              }, 4000);
             
           });
    }

    public Aitem:any=[];
    public print2if=false;
    verremito(item){
      
      var enviar={
        operacion_id:item.pivot.pedido_id
      }
      this.http.post(this.ruta.get_ruta()+'traza/'+item.id,enviar)
           .toPromise()
           .then(
           data => {
             
             this.remito=data;
             this.remito=this.remito.Trazas[0];
             this.remito.solicitud=[];
             this.Aitem=[];
             console.log(item);
             this.Aitem.push(item);
             //this.remito.solicitud.push(item);
             //this.informacion.solicitud=[];
             //this.//.solicitud.push(item);
             //console.log(this.informacion);
             this.print2if=true;
             setTimeout(() => {  
               
                this.print2();
                setTimeout(() => {  
                this.print2if=false;
                //alert('asd');
                }, 2000);
              }, 2000);
             
             
            },
           msg => { 
             console.log(msg);
             alert('¡No existe remito asociado!');
             this.fail=true;
              setTimeout(() => {  
                this.fail=false;
              }, 4000);
             
           });
    }

    print(){
    let printContents, popupWin;
    printContents = document.getElementById('prin').innerHTML;
    popupWin = window.open('', '_blank', 'top=0,left=0,height=100%,width=auto');
    popupWin.document.open();
    popupWin.document.write(`
      <html>
        <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
          
          <style>
            @media print {
              @page { margin: 0; }
              body { margin: 1.6cm; }
              .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
                    float: left;
               }
               .col-sm-12 {
                    width: 100%;
               }
               .col-sm-11 {
                    width: 91.66666667%;
               }
               .col-sm-10 {
                    width: 83.33333333%;
               }
               .col-sm-9 {
                    width: 75%;
               }
               .col-sm-8 {
                    width: 66.66666667%;
               }
               .col-sm-7 {
                    width: 58.33333333%;
               }
               .col-sm-6 {
                    width: 50%;
               }
               .col-sm-5 {
                    width: 41.66666667%;
               }
               .col-sm-4 {
                    width: 33.33333333%;
               }
               .col-sm-3 {
                    width: 25%;
               }
               .col-sm-2 {
                    width: 16.66666667%;
               }
               .col-sm-1 {
                    width: 8.33333333%;
               }
            }
          </style>
        </head>
      <body onload="window.print();window.close()"> ${printContents} </body>
      </html>`
    );
    popupWin.document.close();
    this.print2if=false;
  }
  print2(){
    //alert('ejecuto');
    let printContents, popupWin;
    printContents = document.getElementById('prin2').innerHTML;
    popupWin = window.open('', '_blank', 'top=0,left=0,height=100%,width=auto');
    popupWin.document.open();
    popupWin.document.write(`
      <html>
        <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
          
          <style>
            @media print {
              @page { margin: 0; }
              body { margin: 1.6cm; }
              .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
                    float: left;
               }
               .col-sm-12 {
                    width: 100%;
               }
               .col-sm-11 {
                    width: 91.66666667%;
               }
               .col-sm-10 {
                    width: 83.33333333%;
               }
               .col-sm-9 {
                    width: 75%;
               }
               .col-sm-8 {
                    width: 66.66666667%;
               }
               .col-sm-7 {
                    width: 58.33333333%;
               }
               .col-sm-6 {
                    width: 50%;
               }
               .col-sm-5 {
                    width: 41.66666667%;
               }
               .col-sm-4 {
                    width: 33.33333333%;
               }
               .col-sm-3 {
                    width: 25%;
               }
               .col-sm-2 {
                    width: 16.66666667%;
               }
               .col-sm-1 {
                    width: 8.33333333%;
               }
            }
          </style>
        </head>
      <body onload="window.print();window.close()"> ${printContents} </body>
      </html>`
    );
    popupWin.document.close();
  }
}
