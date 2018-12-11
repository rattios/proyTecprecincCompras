import { Component, OnInit, Input,Pipe, PipeTransform, NgZone  } from '@angular/core';
import {CommonModule, NgClass, DatePipe } from '@angular/common';
import { HttpClient, HttpParams  } from '@angular/common/http';
import 'rxjs/add/operator/toPromise';
import { saveAs } from 'file-saver/FileSaver';
import { RutaService } from '../../../services/ruta.service';
import {MatDatepicker} from '@angular/material/datepicker';
import {DateAdapter} from '@angular/material';
import { DatepickerOptions } from 'ng2-datepicker';
import * as moment from 'moment';


@Component({
  templateUrl: 'comparar_presupuestos.component.html'
})
export class comparar_presupuestosComponent {
  public prov: any;
  public stock: any;
  public proveedores: any;
  public proveedores2: any;
  public productos: any;
  public loading=true;
  public loading2=true;
  public observaciones:any='';
  public vigencia:any=new Date();
  public fechaMinuta:any=moment().format("DD-MM-YYYY");
  public proveedor: any={
          calificacion:null,
          cuit:"",
          email:"",
          estado:"",
          fax:"",
          habilitado:"NO",
          habilitado2: false,
          motivo:"",
          id:0,
          nombre_fantacia:"",
          productos:[],
          razon_social:"",
          telefono:"",
          direccion: "",
          formaPago: "",
          telefonos: []
        };
  public inicioD2:any=new Date();
  public finD2:any=new Date();

  public presupuestosCompara:any=[];
  public productoCompara:any=[];

  constructor(private http: HttpClient, private ruta: RutaService, public zone: NgZone) {
    console.log(moment().format("DD-MM-YYYY"));
    console.log(moment().format('MM'));
    console.log(moment().format('YYYY'));
  }

  ngOnInit(): void {
    var principio:any= moment().format("DD-MM-YYYY");
    var mes:any=moment().format('MM'); 
    var anio:any=moment().format('YYYY');
    principio="01-"+moment().format('MM')+"-"+moment().format('YYYY');
    console.log(principio);
    //var principio2=principio;
    //principio=moment(principio).toDate();

    this.inicioD2=moment().format("DD-MM-YYYY");
   // console.log(moment(principio2).format("DD-MM-YYYY"));
    //var datePipe = new DatePipe("en-US");
    //console.log(datePipe.transform(moment().format("DD-MM-YYYY"), 'dd-MM-yyyy'));
    this.loading=true;
      //this.http.get(this.ruta.get_ruta()+'presupuesto?inicio='+datePipe.transform(principio, 'dd-MM-yyyy')+'&fin='+datePipe.transform(moment().format("DD-MM-YYYY"), 'dd-MM-yyyy'))
        this.http.get(this.ruta.get_ruta()+'presupuesto?inicio='+moment().format("DD-MM-YYYY")+'&fin='+moment().format("DD-MM-YYYY"))
           .toPromise()
           .then(
           data => {
             this.prov=data;
              this.proveedores=this.prov.presupuesto;
              var preprov:any=[];
              console.log(this.proveedores);
              for (var i = 0; i < this.proveedores.length; i++) {
                if(this.proveedores[i].estado==0) {
                  this.proveedores[i].estado2='Generado';
                }else if(this.proveedores[i].estado==1) {
                  this.proveedores[i].estado2='Recibido';
                  preprov.push(this.proveedores[i]);
                }else if(this.proveedores[i].estado==2) {
                  this.proveedores[i].estado2='Cancelado';
                }

                for (var j = 0; j < this.proveedores[i].productos.length; ++j) {
                  this.proveedores[i].productos[j].totales=parseInt(this.proveedores[i].productos[j].cantidad)*this.proveedores[i].productos[j].precio;
                }
              }
              this.productList = preprov;
              this.filteredItems = this.productList;
              this.init();
              this.loading=false;
            },
           msg => { 
             console.log(msg);
             this.loading=false;
           });
  }

  comparar(item){

    this.checkProveedor(item.proveedor,item);
    
  }

  checkProduc(productos){
    console.log(productos);
    var band=0;
    //for (var j = 0; j < productos.length; j++) {
      band=0;
      for (var i = 0; i < this.productoCompara.length; i++) {
        console.log(this.productoCompara[i]);
        console.log(productos);
        if(this.productoCompara[i].id_proveedor==productos.id_proveedor){
          band=1;
        }
      }
      if(band==0) {
        console.log('retorno');
        for (var i = 0; i < productos.length; i++) {
          this.productoCompara.push( {
            id:productos[i].id,
            nombre:productos[i].nombre,
            id_proveedor:productos[0].id_proveedor,
            nombre_proveedor:productos[0].nombre_proveedor,
            cantidad:productos[i].cantidad,
            codigo:productos[i].codigo,
            precio:productos[i].precio
          });
        }

        /*this.productoCompara.sort((a, b) => {
          if (a.id < b.id) return -1;
          else if (a.id > b.id) return 1;
          else return 0;
        });*/
        this.productoCompara.sort(
           function(a, b) {          
              if (a.id === b.id) {
                 // Price is only important when cities are the same
                 return a.precio - b.precio;
              }
              return a.id > b.id ? 1 : -1;
           });
      }
    //}
  }

  ordenar(){
    for (var i = 0; i < this.productoCompara.length; i++){
      
    }
  }

  generar(){

    this.zone.run(()=>{});
    console.log(this.presupuestosCompara);
    console.log(this.productoCompara);
    console.log(this.observaciones);
    console.log('exportar pre');

    let printContents, popupWin;
    printContents = document.getElementById('exportable').innerHTML;
    popupWin = window.open('Minuta ', '_blank', 'top=0,left=0,height=100%,width=auto');
    popupWin.document.open('Minuta ');

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
    var nombre_proveedores= '';
    for (var i = 0; i < this.presupuestosCompara.length; i++) {
      nombre_proveedores+=','+this.presupuestosCompara[i].nombre+' ';
    }
    popupWin.document.title = 'Minuta: '+ nombre_proveedores+' del '+ this.fechaMinuta;
    popupWin.document.close();
  }

  cancelar2(){
    console.log(this.presupuestosCompara);
    console.log(this.productoCompara);
    this.presupuestosCompara=[];
    this.productoCompara=[];
  }

  checkProveedor(proveedor,productos){
    console.log(proveedor);
    console.log(productos);
    var band=0;
    //for (var j = 0; j < productos.length; j++) {
      band=0;
      for (var i = 0; i < this.presupuestosCompara.length; i++) {
        console.log(this.presupuestosCompara[i]);
        console.log(proveedor);
        if(this.presupuestosCompara[i].id==proveedor.id){
          band=1;
        }
      }
      if(band==0) {
        console.log('retorno');
        this.presupuestosCompara.push({
          id:proveedor.id,
          nombre:proveedor.razon_social,
          fecha:productos.created_at
        });
        console.log(productos.productos);
        //for (var i = 0; i < productos.productos.length; i++) {
          productos.productos[0].id_proveedor=productos.proveedor.id;
          productos.productos[0].nombre_proveedor=productos.proveedor.razon_social;
          this.checkProduc(productos.productos);
        //}
      }
    //}
  }

  buscar(){
    this.loading=true;
    var datePipe = new DatePipe("en-US");
    this.http.get(this.ruta.get_ruta()+'presupuesto?inicio='+datePipe.transform(this.inicioD2, 'dd-MM-yyyy')+'&fin='+datePipe.transform(this.finD2, 'dd-MM-yyyy'))
         .toPromise()
         .then(
         data => {
           this.prov=data;
            this.proveedores=this.prov.presupuesto;
            console.log(this.proveedores);
            this.productList = this.proveedores;
            this.filteredItems = this.productList;
            this.init();
            this.loading=false;
          },
         msg => { 
           console.log(msg);
           this.loading=false;
         });
  }
  public enviado:any={
      proveedor:{
        razon_social:'',
        nombre_fantasia:'',
        cuit:'',
        telefono:'',
        email:'',
      },
      pedido_id:0,
      estado:0,
      proveedor_id:0,
      productos:[],
      observaciones:''
    };
  public verDatos=false;
  ver(item){
    this.enviado=item;
    this.verDatos=true;
  }
  volver(){
    this.enviado={
      proveedor:{
        razon_social:'',
        nombre_fantasia:'',
        cuit:'',
        telefono:'',
        email:'',
      },
      pedido_id:0,
      estado:0,
      proveedor_id:0,
      productos:[],
      observaciones:''
    };
    this.verDatos=false;
  }
  public aEnviar:any={
      razon_social:'',
      nombre_fantasia:'',
      cuit:'',
      telefono:'',
      email:'',
      pedido_id:21,
      proveedor_id:0,
      productos:[],
      observaciones:'',
      documento:''
    };

  guardarCambios(){
    this.loading=true;
      if(this.enviado.productos.length>0) {
        this.aEnviar.razon_social=this.enviado.proveedor.razon_social,
        this.aEnviar.nombre_fantasia=this.enviado.proveedor.nombre_fantasia,
        this.aEnviar.cuit=this.enviado.proveedor.cuit,
        this.aEnviar.telefono=this.enviado.proveedor.telefono,
        this.aEnviar.email=this.enviado.proveedor.email,
        this.aEnviar.proveedor_id=this.enviado.proveedor.id;
        this.aEnviar.productos=JSON.stringify(this.enviado.productos);
        this.aEnviar.observaciones=this.enviado.observaciones;
        this.aEnviar.vigencia=this.enviado.vigencia;
        console.log(this.enviado.id);
        this.http.put(this.ruta.get_ruta()+'presupuesto/'+this.enviado.id,this.aEnviar)
           .toPromise()
           .then(
           data => {
             console.log(data);
              this.loading=false;
              alert('Exito!');

            },
           msg => { 
             console.log(msg);
             this.loading=false;
           });
      }
      console.log(this.aEnviar);
  }
  recibido(){
    this.loading=true;
      if(this.enviado.productos.length>0) {
        var send={
          estado:1
          }        
        console.log(this.enviado.id);
        this.http.put(this.ruta.get_ruta()+'presupuesto/'+this.enviado.id,send)
           .toPromise()
           .then(
           data => {
             console.log(data);
              this.loading=false;
              this.ngOnInit();
              this.volver();
              alert('Exito!');

            },
           msg => { 
             console.log(msg);
             this.loading=false;
           });
      }
  }
  cancelar(){
    this.loading=true;
      if(this.enviado.productos.length>0) {
        var send={
          estado:2
          }        
        console.log(this.enviado.id);
        this.http.put(this.ruta.get_ruta()+'presupuesto/'+this.enviado.id,send)
           .toPromise()
           .then(
           data => {
             console.log(data);
              this.loading=false;
              this.ngOnInit();
              this.volver();
              alert('Exito!');

            },
           msg => { 
             console.log(msg);
             this.loading=false;
           });
      }
  }
  
  mover(){
    console.log('asd');
    this.volver();
  }



     //-------------------------------------------------------------------------------------------------------------------------
   
   filteredItems : any;
   productList : any;
   pages : number = 4;
   pageSize : number = 5;
   pageNumber : number = 6;
   currentIndex : number = 1;
   items: any;
   pagesIndex : Array<number>;
   pageStart : number = 1;
   inputName : string = '';

   init(){
         this.currentIndex = 1;
         this.pageStart = 1;
         this.pages = 4;

         this.pageNumber = parseInt(""+ (this.filteredItems.length / this.pageSize));
         if(this.filteredItems.length % this.pageSize != 0){
            this.pageNumber ++;
         }
    
         if(this.pageNumber  < this.pages){
               this.pages =  this.pageNumber;
         }
       
         this.refreshItems();
         console.log("this.pageNumber :  "+this.pageNumber);
   }
   FilterByName(){
      this.filteredItems = [];
      if(this.inputName != ""){
            for (var i = 0; i < this.productList.length; ++i) {

              if (this.productList[i].razon_social.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].cuit.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].telefono.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList[i].email.toUpperCase().indexOf(this.inputName.toUpperCase())>=0) {
                 this.filteredItems.push(this.productList[i]);
              }
            }

            // this.productList.forEach(element => {
            //     if(element.nombre.toUpperCase().indexOf(this.inputName.toUpperCase())>=0){
            //       this.filteredItems.push(element);
            //    }
            // });
      }else{
         this.filteredItems = this.productList;
      }
      console.log(this.filteredItems);
      this.init();
   }
   fillArray(): any{
      var obj = new Array();
      for(var index = this.pageStart; index< this.pageStart + this.pages; index ++) {
                  obj.push(index);
      }
      return obj;
   }
 refreshItems(){
               this.items = this.filteredItems.slice((this.currentIndex - 1)*this.pageSize, (this.currentIndex) * this.pageSize);
               this.pagesIndex =  this.fillArray();
   }
   prevPage(){
      if(this.currentIndex>1){
         this.currentIndex --;
      } 
      if(this.currentIndex < this.pageStart){
         this.pageStart = this.currentIndex;
      }
      this.refreshItems();
   }
   nextPage(){
      if(this.currentIndex < this.pageNumber){
            this.currentIndex ++;
      }
      if(this.currentIndex >= (this.pageStart + this.pages)){
         this.pageStart = this.currentIndex - this.pages + 1;
      }
 
      this.refreshItems();
   }
    setPage(index : number){
         this.currentIndex = index;
         this.refreshItems();
    }

    //-------------------------------------------------------------------------------------------------------------------------
   
   filteredItems2 : any;
   productList2 : any;
   pages2 : number = 4;
   pageSize2 : number = 5;
   pageNumber2 : number = 0;
   currentIndex2 : number = 1;
   items2: any;
   pagesIndex2 : Array<number>;
   pageStart2 : number = 1;
   inputName2 : string = '';

   init2(){
         this.currentIndex2 = 1;
         this.pageStart2 = 1;
         this.pages2 = 4;

         this.pageNumber2 = parseInt(""+ (this.filteredItems2.length / this.pageSize2));
         if(this.filteredItems2.length % this.pageSize2 != 0){
            this.pageNumber2 ++;
         }
    
         if(this.pageNumber2  < this.pages2){
               this.pages2 =  this.pageNumber2;
         }
       
         this.refreshItems2();
         console.log("this.pageNumber :  "+this.pageNumber2);
   }
   FilterByName2(){
      this.filteredItems2 = [];
      if(this.inputName2 != ""){
            for (var i = 0; i < this.productList2.length; ++i) {

              if (this.productList2[i].stock==this.inputName2) {
                 this.filteredItems2.push(this.productList2[i]);
              }else if (this.productList2[i].nombre.toUpperCase().indexOf(this.inputName2.toUpperCase())>=0) {
                 this.filteredItems2.push(this.productList2[i]);
              }else if (this.productList2[i].precio==this.inputName2) {
                 this.filteredItems.push(this.productList[i]);
              }else if (this.productList2[i].codigo==this.inputName2) {
                 this.filteredItems2.push(this.productList2[i]);
              }
              
            }

            // this.productList.forEach(element => {
            //     if(element.nombre.toUpperCase().indexOf(this.inputName.toUpperCase())>=0){
            //       this.filteredItems.push(element);
            //    }
            // });
      }else{
         this.filteredItems2 = this.productList2;
      }
      //console.log(this.filteredItems2);
      this.init2();
   }
   fillArray2(): any{
      var obj2 = new Array();
      for(var index2 = this.pageStart2; index2< this.pageStart2 + this.pages2; index2 ++) {
                  obj2.push(index2);
      }
      return obj2;
   }
 refreshItems2(){
               this.items2 = this.filteredItems2.slice((this.currentIndex2 - 1)*this.pageSize2, (this.currentIndex2) * this.pageSize2);
               this.pagesIndex2 =  this.fillArray();
               console.log(this.items2);
   }
   prevPage2(){
      if(this.currentIndex2>1){
         this.currentIndex2 --;
      } 
      if(this.currentIndex2 < this.pageStart2){
         this.pageStart2 = this.currentIndex2;
      }
      this.refreshItems2();
   }
   nextPage2(){
      if(this.currentIndex2 < this.pageNumber2){
            this.currentIndex2 ++;
      }
      if(this.currentIndex2 >= (this.pageStart2 + this.pages2)){
         this.pageStart2 = this.currentIndex2 - this.pages2 + 1;
      }
 
      this.refreshItems2();
   }
    setPage2(index : number){
         this.currentIndex2 = index;
         this.refreshItems2();
    }
    
}
