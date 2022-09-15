<template>
  <div class="card" v-if="tokensStore.getters['loaded']">
    <header class="card-header">
      <h3 class="card-header-title">LIVE Credentials</h3>
    </header>
    <section class="card-content">
      <div class="content">
        <p>List of active API Keys.</p>
        <p>You may use our API for your integrations. You have to read <a href="/docs">Documentation</a> for details.</p>
        <b-field v-for="userToken in tokens"
          :key="userToken.id"
          :label="userToken.name">
          <div>
            <b-field>
              <b-input :value="userToken.token" class="is-white" readonly />
              <div class="button is-success" @click="copy(userToken)">
                <b-icon icon="content-copy" />
              </div>
            </b-field>
            <p class="help">Make sure token properly saved. (<a class="link" @click="revoke(userToken)">Revoke</a>)</p>
          </div>
        </b-field>
        <b-field v-if="!tokens.length || createToken" message="Provide token name to identify it later.">
          <b-field>
            <b-input
              type="text"
              name="token-name"
              v-model="newToken.name"
              v-validate="'required'"
              placeholder="Token name"
            />
            <button class="button is-primary" @click="create">Create</button>
          </b-field>
        </b-field>
        <b-field v-else>
          <a class="link button is-outlined is-rounded" @click="createToken = true">Create new token</a>
        </b-field>
      </div>
    </section>
  </div>
</template>
<script>
import { createStore } from 'lib/store/grid-store';
import jsona from 'lib/jsona';

export default {
  data() {
    return {
      createToken: false,
      newToken: {
        name: null,
      },
      tokensStore: createStore('tokens', {
        baseURL: () => `/api/user/${this.me.id}/`,
      }),
    }
  },
  computed: {
    tokens: ({ tokensStore }) => tokensStore.state.collection,
    columns: () => ([
      {
        field: 'name',
        label: 'Name',
      },
      {
        field: 'token',
        label: 'Token',
      },
      {
        field: 'createdAt',
        label: 'Created At',
        centered: true,
      },
      {
        field: 'updatedAt',
        label: 'Updated At',
        centered: true,
      },
    ])
  },
  created() {
    this.tokensStore.dispatch('load');
    // this.$http.get('/oauth/personal-access-tokens')
    //   .then(rsp => rsp.data)
    //   .then(tokens => {
    //     if (Array.isArray(tokens)) {
    //       this.tokens = tokens;
    //       return;
    //     }
    //     throw new Error('Failed get user tokens');
    //   })
  },
  methods: {
    copy(token) {
      navigator.clipboard.writeText(token.token)
        .then(
          () => this.$toast.open({ message: `API Token copied to clipboard.`, type: 'is-warning' }),
          () => this.$toast.open({ message: 'Failed to copy API token.', type: 'is-danger' })
        );
    },
    revoke(token) {
      this.$dialog.confirm({
        title: `Removing API token`,
        message: `Token <b>${token.name}</b> will be removed and access removed.`,
        // type: 'is-success',
        onConfirm: () => {
          this.$http.delete(`/api/user_token/${token.id}`)
            .then(() => this.$toast.open({ type: 'is-success', message: 'Token revoked.' }))
            .then(() => this.tokensStore.dispatch('load'));
        },
      });
    },
    create() {
      this.$validator.validateAll()
        .then((result) => {
          if (result) {
            const { name } = this.newToken;
            this.$http.post('/api/user_token', { data: { attributes: { name } } })
              .then(() => this.tokensStore.dispatch('load'))
            .then(() => this.$toast.open({ type: 'is-success', message: 'Token revoked.' }))
              .then(() => this.createToken = false);
          }
        });
    }
  }
};
</script>
