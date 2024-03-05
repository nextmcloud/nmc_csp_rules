window.addEventListener('load', function() {
    if (typeof utag === 'undefined' || !utag.view()) return
    // Plus button in a breadcrumb
    let plusBtnContainer = document.querySelector('.files-controls .actions.creatable')
    plusBtnContainer?.addEventListener("click", function(event) {
        let menuOption = event.target.closest('[data-action]')
        if (menuOption) {
            utag.view({
                wt_link_id: `plus.button.${menuOption.attributes['data-action'].value}`,
                page_content_id: "files.breadcrumb",
                page_type: "files"
            })
        }
    })

    // Table head listener
    let theadContainer = document.querySelector('#app-content-files table.files-filestable thead')
    theadContainer?.addEventListener("mousedown", function(event) {
        if (event.target.closest('.column-selection')) {
            utag.view({
                wt_link_id: "checkbox.select-all",
                page_content_id: "files.tablehead",
                page_type: "files"
            })
            return
        }
        let menuOption = event.target.closest('[data-action]')
        if (menuOption) {
            utag.view({
                wt_link_id: `button.${menuOption.attributes['data-action'].value}`,
                page_content_id: "files.tablehead",
                page_type: "files"
            })
        }
    })

    // Table body listener
    const registerTableBodyEvent = function(event) {
        if (event.target.closest('.action.action-share')) {
            utag.view({
                wt_link_id: "button.shared",
                page_content_id: "files.tablebody",
                page_type: "files"
            })
        } else if (event.target.closest('td.selection')) {
            utag.view({
                wt_link_id: "checkbox.select-file",
                page_content_id: "files.tablebody",
                page_type: "files"
            })
        } else if (event.target.closest('.fileActionsMenu.popovermenu')) {
            let menuItem = event.target.closest('[data-action]')
            menuItem && utag.view({
                wt_link_id: `button.${menuItem.attributes['data-action'].value}`,
                page_content_id: "files.tablebody.more",
                page_type: "files"
            })
        }
    }

    let tbodyContainer = document.querySelector('#app-content-files table.files-filestable tbody')
    tbodyContainer?.addEventListener("mousedown", (event) => registerTableBodyEvent(event))

    // Files navigation container
    let navContainer = document.querySelector('#app-navigation-vue')
    navContainer?.addEventListener("mousedown", function(event) {
        let navigation = event.target.closest('[data-cy-files-navigation-item]')
        if (navigation) {
            utag.view({
                wt_link_id: `link.${navigation.attributes['data-cy-files-navigation-item'].value}`,
                page_content_id: "files.sidebarnav",
                page_type: "files"
            })
        }
    })

    // Photos navigation container
    let navMediaContainer = document.querySelector('#app-navigation-vue')
    navMediaContainer?.addEventListener("mousedown", function(event) {
        let navigation = event.target.closest('[data-id-app-nav-item]')
        if (navigation) {
            utag.view({
                wt_link_id: `link.${navigation.attributes['data-id-app-nav-item'].value}`,
                page_content_id: "photos.sidebarnav",
                page_type: "photos"
            })
        }
    })

    // Toggle grid/list view button listener
    let toggleView = document.querySelector('#view-toggle')
    toggleView?.addEventListener('click', function() {
        utag.view({
            wt_link_id: 'button.toggleview',
            page_content_id: "files.list",
            page_type: "files"
        })
    })

    // Header - navigation
    let headerLeft = document.querySelector('#header .header-left')
    headerLeft?.addEventListener("mousedown", function(event) {
        let navigation = event.target.closest('[data-app-id]')
        if (navigation) {
            utag.view({
                wt_link_id: `link.${navigation.attributes['data-app-id'].value}`,
                page_content_id: "header.left",
                page_type: "header"
            })
        }
    })

    // Header - options
    let headerRight = document.querySelector('#header .header-right')
    headerRight?.addEventListener("mousedown", function(event) {
        let navigation = event.target.closest('[id]')
        if (navigation) {
            utag.view({
                wt_link_id: `link.${navigation.attributes['id'].value}`,
                page_content_id: "header.right",
                page_type: "header"
            })
        }
    })
})
