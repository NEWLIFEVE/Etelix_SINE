function reinit()
{
    $('.dropdown-menu').dropdown({effect: 'slide'});
}

$(function(){
    $("[data-load]").each(function(){
        $(this).load($(this).data("load"), function(){
            reinit();
        });
    });

    window.prettyPrint && prettyPrint();

    $(".history-back").on("click", function(e){
        e.preventDefault();
        history.back();
        return false;
    })
})


$(function() {
    if ($('nav > .side-menu').length > 0) {
        var side_menu = $('nav > .side-menu');
        var fixblock_pos = side_menu.position().top;
        $(window).scroll(function(){
            if ($(window).scrollTop() > fixblock_pos){
                side_menu.css({'position': 'fixed', 'top':'65px', 'z-index':'1000'});
            } else {
                side_menu.css({'position': 'static'});
            }
        })
    }

    $(window).scroll(function(){
        if ($(window).scrollTop() > $('header').height()) {
            $("header > .navigation-bar")
                .addClass("fixed-top")
                .addClass("opacity shadow")
            ;
        } else {
            $("header > .navigation-bar")
                .removeClass("fixed-top")
                .removeClass("opacity shadow")
            ;
        }
    });
});

/**
*funciones encargadas de hacer el slider para mostrar interfaz
*/
var navegar=function()
{
    this.enlaces='a#flecha-forward'/*, a#flecha-backward'*/;
    this.main='#capa';
    this.nueva='.vistas';
}
/**
 *
 */
navegar.prototype.run=function()
{
    this.boton=$(this.enlaces);
    this.objetoMain=$(this.main);
    this.objetoNueva=$(this.nueva);
    this.pisaAqui();
}
/**
 *
 */
navegar.prototype.pisaAqui=function()
{
    var self=this;
    this.boton.on('click',function(e)
    {
        e.preventDefault();
        self.url=$(this).attr('href');
        if(self.url=="/")
        {
            self.vuelta();
        }
        else
        {
            self.ida();
        }
    });
}
/**
 *
 */
navegar.prototype.ida=function()
{
    var self=this;
    this.objetoNueva.load(this.url,function()
    {
        self.objetoMain.toggle('slide');
        self.objetoNueva.fadeIn('fast');
        $SINE.UI.init();
        $SINE.AJAX.init();
    });
}
/**
 *
 */
navegar.prototype.vuelta=function()
{
    var self=this;
    this.objetoNueva.load(this.url,function()
    {
        self.objetoNueva.fadeOut('slow');
        self.objetoMain.toggle('slide');
    });
}