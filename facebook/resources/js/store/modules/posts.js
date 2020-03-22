const state = {
    newsPosts: null,
    newPostsStatus: null,
};

const getters = {
    newsPosts: state => {
        return state.newsPosts;
    },
    newsStatus: state => {
        return {
            newsPostsStatus: state.newsPostsStatus
        }
    }
};

const actions = {
    fetchNewsPosts({commit, state}) {
        commit('setPostsStatus', 'loading');

        axios.get('api/posts')
            .then(res => {
                commit('setPosts', res.data)
                commit('setPostsStatus', 'success');
            })
            .catch(error => {
                commit('setPosts', res.data)
                commit('setPostsStatus', 'error');
            })

    }
};

const mutations = {
    setPosts(state, posts) {
        state.newsPosts = posts;
    },
    setPostsStatus(state, status) {
        state.newsPostsStatus = status;
    },
};

export default {
    state, getters, actions, mutations,
}
