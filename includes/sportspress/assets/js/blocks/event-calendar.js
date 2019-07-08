import apiFetch from '@wordpress/api-fetch';
console.log(wp.api.collections.Posts());

wp.blocks.registerBlockType('sportspress/event-calendar', {
  title: strings.event_calendar,
  icon: 'calendar',
  category: 'sportspress',
  attributes: {
    title: {
      type: 'string'
    },
    id: {
      type: 'number'
    },
    status: {
      type: 'string'
    },
    date: {
      type: 'string'
    },
    date_from: {
      type: 'string'
    },
    date_to: {
      type: 'string'
    },
    date_past: {
      type: 'number'
    },
    date_future: {
      type: 'number'
    },
    date_relative: {
      type: 'number'
    },
    day: {
      type: 'string'
    },
    show_all_events_link: {
      type: 'number'
    },

    content: {type: 'string'},
    color: {type: 'string'}
  },
  
  edit: function(props) {
    function updateContent(event) {
      props.setAttributes({content: event.target.value})
    }
    function updateColor(value) {
      props.setAttributes({color: value.hex})
    }
    return React.createElement(
      wp.components.Panel,
      {header: strings.event_calendar},
      React.createElement(
        wp.components.PanelBody,
        {title: strings.properties},
        React.createElement(
          wp.components.TextControl,
          {label: strings.title, type: "text", value: props.attributes.title}
        ),
        React.createElement(
          wp.components.SelectControl,
          {label: strings.select_calendar, options: [{label: strings.all, value: 0}].concat(posts.events.map(post => {
            return {label: post.post_title, value: post.ID}
          }))}
        )
      )
    );
  },

  save: function(props) {
    return wp.element.createElement(
      "h3",
      { style: { border: "3px solid " + props.attributes.color } },
      props.attributes.content
    );
  }
})