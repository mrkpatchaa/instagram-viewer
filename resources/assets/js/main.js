var React = require('react');
var ReactDOM = require('react-dom');

var InfiniteScroll = require('react-infinite-scroll-component');
// var PhotoSwipe = require('react-photoswipe');
// import {PhotoSwipe, PhotoSwipeGallery} from 'react-photoswipe';
// var PhotoSwipeGallery = require('react-photoswipe');
// export default PhotoSwipe;
// export default PhotoSwipeGallery;

// let items = [
//     {
//         idx: 'Curitiba',
//         title: 'Brazil',
//         src: 'http://lorempixel.com/1000/900/sports',
//         thumbnail: 'http://lorempixel.com/100/90/sports',
//         w: 1000,
//         h: 900
//     },
//     {
//         idx: 'New York',
//         title: 'United States',
//         src: 'http://lorempixel.com/1000/900/animals',
//         thumbnail: 'http://lorempixel.com/100/90/animals',
//         w: 1000,
//         h: 900
//     },
//     {
//         idx: 'Tokyo',
//         title: 'Japan',
//         src: 'http://lorempixel.com/1000/900/nature',
//         thumbnail: 'http://lorempixel.com/100/90/nature',
//         w: 1000,
//         h: 900
//     }
// ];

class Photos extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            photos: [],
            loadMore: true,
            loadIdx: 1,
            loadType: document.getElementById("photos").getAttribute('data-type')
        };

        this.loadMorePhotos();
    }

    loadMorePhotos() {
        // Set loadMore to false and ReactInfinitum will be blocked
        this.setState({
            loadMore: false
        });

        // Simulate an AJAX request
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            method: "POST",
            url: '/instagram/api',
            data: {
                idx: this.state.loadIdx,
                type: this.state.loadType
            },
            success: (data) => {
                console.log(data);
                if(data.status == 1) {
                    this.setState({
                        photos: this.state.photos.concat(data.data),
                        loadMore: true,
                        loadIdx: this.state.loadIdx + 1
                    });
                }
                else {
                    this.setState({
                        loadMore: false
                    });
                }
            }
        })
        .fail(() => {
            // No more photos
            this.setState({
                loadMore: false
            });
        });
        // ajax.get('/instagram/' + document.getElementById("photos").getAttribute('data-type'))
        // .then(res => {
        //     this.setState({
        //         photos: this.state.photos.concat(res),
        //         loadMore: true
        //     });
        // })
        // .catch(() => {
        //     // No more photos
        //     this.setState({
        //         loadMore: false
        //     });
        // });
    }

    render() {
        return (
            <InfiniteScroll
                next={this.loadMorePhotos.bind(this)}
                hasMore={this.state.loadMore}
                loader={<h4>Loading...</h4>}>
                {this.state.photos.map((photo, i) => {
                    return (
                        <figure key={i} className="photo col-xs-1 col-sm-6 col-md-3 text-center">
                            <a href={photo.src} data-size={photo.w + 'x' + photo.h}>
                                <img src={photo.thumbnail} alt={photo.title} />
                            </a>
                        </figure>
                    );
                })}
            </InfiniteScroll>
        );
    }
}

// export default Photos;

ReactDOM.render(
    <Photos />,
    document.getElementById('photos')
);
