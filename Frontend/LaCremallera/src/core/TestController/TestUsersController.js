import datos from "./DatosUsuarios";

const $usuariosController=(function() {
    console.log("Inicializando usuarios Controller TEST FALSEADO");
    
    if (!localStorage.getItem('usuarios')) {
        localStorage.setItem('usuarios', JSON.stringify(datos));
    }
    let usuarios = JSON.parse(localStorage.getItem('usuarios'));

    function siguienteUsuarioId() {
        let maxId = Math.max(...usuarios.map(p => p.usuarioId), 0);
        return maxId + 1;
    }
    
    function getUsuarios(){
        console.log("usuariosController: getUsuarios");
        return [...usuarios];
    }

    function getUsuario(usuarioId){
        let index = usuarios.findIndex(p => p.usuarioId == usuarioId);

        if (index !== -1) {
            return usuarios[index];
        }
    }
    return {
        getUsuarios,
        getUsuario
    }
})();

export default $usuariosController;