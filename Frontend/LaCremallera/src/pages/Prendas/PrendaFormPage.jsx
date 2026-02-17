import { useState, useEffect, act } from "react";
import { useNavigate, useParams } from "react-router-dom";

import $prendasController from "../../core/TestController/TestPrendasController";

function PrendaFormPage(){
    const [prendaData,setPrendaData]=useState({
        prendaId:0,
        usuarioId:0,
        tipo:"",
        descripcion:"",
        color:"",
        talla:""
    });

    const navegar=useNavigate();

    const {id}=useParams();

    const cargarDatos=async()=>{
        console.log("Cargando Datos");

        if(id!=0){
            console.log("modo update");

            let datos=await $prendasController.getPrenda(id);

            //comprobar success
            setPrendaData(datos);
        }
    }

    const handleOnSubmit=(evento)=>{
        evento.preventDefault();
        console.log("PrendaFormPage: onSubmit");
    }

    const enviarDatos=async()=>{
        console.log("Enviar datos");

        let success;
        let statusCode=0;

        if(id!=0){
            console.log("actualizar");
            prendaData.prendaId=id;
            let result= await $prendasController.updatePrenda(prendaData);
            navegar("/prendas");
        }else{
            console.log("crear");
            let result=await $prendasController.createPrenda(prendaData);

            navegar("/prendas");
        }

        //todo: implementar operaciones
        // y manejo de errores
    }

    const handleOnCancel=(evento)=>{
        evento.preventDefault();
        navegar("/prendas");
    }

    const handleOnChange=(evento)=>{
        const {name,value}=evento.target;
        let actualizar={...prendaData,[name]:value};
        setPrendaData(actualizar);
    }

    useEffect(()=>{
        cargarDatos(id);
    },[id]);

    return (
        <div>
            <div>Formulario create/update prendas</div>

            <form onSubmit={handleOnSubmit}>
                <div>Formulario datos de Prendas</div>

                <div>
                    <div>usuario</div>
                    {/* Hacer select y options */}
                    <input type="number" name="usuarioId" id="usuarioId" value={prendaData.usuarioId}/>
                </div>

                <div>
                    <div>tipo</div>
                    <input type="text" name="tipo" id="tipo" value={prendaData.tipo}/>
                </div>

                <div>
                    <div>descripcion</div>
                    <input type="text" name="descripcion" id="descripcion" value={prendaData.descripcion} />
                </div>

                <div>
                    <div>color</div>
                    <input type="text" name="color" id="color" value={prendaData.color} />
                </div>

                <div>
                    <div>talla</div>
                    <input type="text" name="talla" id="talla" value={prendaData.talla}/>
                </div>

                <div>
                    <button type="submit">Enviar datos</button>
                    <button onClick={()=>{handleOnCancel();}}>Cancelar</button>
                </div>
            </form>
        </div>
    )
}

export default PrendaFormPage;