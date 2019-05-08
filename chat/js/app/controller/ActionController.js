//==============================================================================
//
//  Controller handling global actions
//
//==============================================================================

(function(app, _)
{
    var ActionController = app.ActionController = function() {};

    _.extend(ActionController.prototype, {

        run : function(actionName, attrs)
        {
            if(this.actions[actionName])
            {
                this.actions[actionName].apply(this, attrs);
            }
        },

        actions : {

            showTalk : function(talkId)
            {
                app.trigger('talk.show', talkId);
            }
        }
    });

})(window.Application, _);
