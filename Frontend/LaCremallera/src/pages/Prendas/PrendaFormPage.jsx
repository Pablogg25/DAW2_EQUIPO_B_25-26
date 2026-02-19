import { useState, useEffect, act } from "react";
import { useNavigate, useParams } from "react-router-dom";

// import $prendasController from "../../core/TestController/TestPrendasController";
// import $usuariosController from "../../core/TestController/TestUsersController";
import $prendasController from "../../core/PrendasController";
import $usersController from "../../core/UsersController";

function PrendaFormPage() {
    const [prendaData, setPrendaData] = useState({
        prendaId: 0,
        usuarioId: 0,
        tipo: "",
        descripcion: "",
        color: "",
        talla: ""
    });

    const [usuariosData, setUsuariosData] = useState([]);

    const navegar = useNavigate();

    const { id } = useParams();

    const cargarDatos = async () => {
        console.log("Cargando Datos");

        let datosUsuario = await $usersController.getUsers();

        setUsuariosData(datosUsuario.data);

        if (id != 0) {
            console.log("modo update");

            let datos = await $prendasController.getPrenda(id);

            //comprobar success
            if (datos.success) {
                setPrendaData(datos.data);
            } else {
                alert("Ha surgido un error inesperado al procesar la petición");
                navegar("/prendas");
            }

        }
    }

    const handleOnSubmit = (evento) => {
        evento.preventDefault();
        console.log("PrendaFormPage: onSubmit");

        enviarDatos();
    }

    const enviarDatos = async () => {
        console.log("Enviar datos");
        console.log(prendaData);

        let success;
        let result;

        if (id != 0) {
            console.log("actualizar");
            let setearPrenda = { ...prendaData, ["prendaId"]: id };
            result = await $prendasController.updatePrenda(setearPrenda);
            success = result.success;
        } else {
            console.log("crear");
            result = await $prendasController.createPrenda(prendaData);
            success = result.success;

        }

        //todo: implementar operaciones
        // y manejo de errores

        if (success) {
            alert("Datos enviados con éxito");
            navegar("/prendas");
        } else {
            if (result.estado == 400) {
                alert("Error de validación, compruebe que los campos están correctamente formateados");
            } else {
                alert("Error, ha surgido un error al procesar su petición.\nCodigo de error: " + result.estado);

            }

        }
    }

    const handleOnCancel = (evento) => {
        evento.preventDefault();
        navegar("/prendas");
    }

    const handleOnChange = (evento) => {
        const { name, value } = evento.target;
        let actualizar = { ...prendaData, [name]: value };
        setPrendaData(actualizar);
    }

    useEffect(() => {
        cargarDatos(id);
    }, [id]);

    return (
        <div>
            <div>Formulario create/update prendas</div>

            <form onSubmit={handleOnSubmit}>
                <div>Formulario datos de Prendas</div>

                <div>
                    <div>usuario</div>
                    {/* Hacer select y options */}
                    {/* <input type="number" name="usuarioId" id="usuarioId" value={prendaData.usuarioId} onChange={handleOnChange}/> */}

                    <select name="usuarioId" id="usuarioId" value={prendaData.usuarioId} onChange={handleOnChange}>
                        {usuariosData.map((elemento) => {
                            return (
                                <option
                                    key={elemento.usuarioId}
                                    value={elemento.usuarioId}>
                                    {elemento.nombre}
                                </option>
                            )
                        })}
                    </select>
                </div>

                <div>
                    <div>tipo</div>
                    <input type="text" name="tipo" id="tipo" value={prendaData.tipo} onChange={handleOnChange} />
                </div>

                <div>
                    <div>descripcion</div>
                    <input type="text" name="descripcion" id="descripcion" value={prendaData.descripcion} onChange={handleOnChange} />
                </div>

                <div>
                    <div>color</div>
                    <input type="text" name="color" id="color" value={prendaData.color} onChange={handleOnChange} />
                </div>

                <div>
                    <div>talla</div>
                    <input type="text" name="talla" id="talla" value={prendaData.talla} onChange={handleOnChange} />
                </div>

                <div>
                    <button type="submit">Enviar datos</button>
                    <button onClick={handleOnCancel}>Cancelar</button>
                </div>
            </form>
        </div>
    )
}

export default PrendaFormPage;