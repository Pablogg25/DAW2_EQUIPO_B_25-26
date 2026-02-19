import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";

import "./PrendasPage.css";
import $prendasController from "../../core/TestController/TestPrendasController";
import $usuariosController from "../../core/TestController/TestUsersController";

function PrendasPage() {
    const [prendas, setPrendas] = useState([]);
    const [usuarios,setUsuarios]=useState([]);

    const navegar = useNavigate();

    const cargarDatos = async () => {
        console.log("Cargando datos");

        //obtener datos de controlador

        //si datos de usuario no estan inicializados cargar:
        if(usuarios.length==0){
            let datosUsuario=await $usuariosController.getUsuarios();
            setUsuarios(datosUsuario);

        }
        let datos = await $prendasController.getPrendas();
        setPrendas(datos);

        //si success guardar, sino dar aviso
    }

    const onCreatePrenda = () => {
        console.log("On create Prenda");

        //navegar a formulario
        navegar("/prendas/0");
    }

    const onEditPrenda = (prendaId) => {
        console.log("On edit prenda id: " + prendaId);

        //navegar al id
        navegar("/prendas/"+prendaId);

    }

    const onDeletePrenda = async (prendaId) => {
        console.log("On delete prenda id: " + prendaId);

        if (prendaId) {
            //hacer confirm para borrar el usuario y luego recargar datos
            let result=await $prendasController.deletePrenda(prendaId);

            if(result){
                cargarDatos();
                navegar("/prendas");
            }
        }
    }

    useEffect(() => {
        cargarDatos();
    }, [])

    function getUsername(userId){
        let index=usuarios.findIndex(u=>u.usuarioId==userId);
        if(index!=-1){
            return usuarios[index].nombre;
        }
        return "not found";
    }


    return (
        <>
            <div>Prendas page</div>
            <div>Lista para realizar crud sobre prendas</div>

            <div>
                <button onClick={() => {
                    onCreatePrenda();
                }}>Crear prenda</button>
            </div>

            <div>
                {/* Lista prendas */}
                <div className="tableRow">
                    <div>
                        <strong>Id</strong>
                    </div>
                    <div>
                        <strong>usuario</strong>
                    </div>
                    <div>
                        <strong>Tipo</strong>
                    </div>
                    <div>
                        <strong>Descripción</strong>
                    </div>
                    <div>
                        <strong>color</strong>
                    </div>
                    <div>
                        <strong>Talla</strong>
                    </div>
                    <div>
                        <strong>Operaciones</strong>
                    </div>
                </div>
                {/* filas */}

                {
                    prendas.map((elemento) => {
                        return (
                            <div key={elemento["prendaId"]} className="tableRow">
                                <div>{elemento["prendaId"]}</div>
                                <div>{getUsername(elemento["usuarioId"])}</div>
                                <div>{elemento["tipo"]}</div>
                                <div>{elemento["descripcion"]}</div>
                                <div>{elemento["color"]}</div>
                                <div>{elemento["talla"]}</div>
                                <div>
                                    <button onClick={() => { onEditPrenda(elemento["prendaId"]) }}>Ver/editar</button>
                                    <button onClick={() => { onDeletePrenda(elemento["prendaId"]) }}>Eliminar</button>
                                </div>
                            </div>
                        );
                    })
                }
            </div>
        </>
    )
}

export default PrendasPage;